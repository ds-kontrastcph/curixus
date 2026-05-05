<?php
/**
 * Contact Form 7 integration for the investment application form.
 *
 * Routes the submission either to the Curixus inbox (default CF7 mail) or to
 * an automatic rejection email based on the business thresholds defined with
 * the client:
 *  - Capital sought  > 10,000,000 DKK   -> rejection
 *  - Pre-money valuation > 100,000,000 DKK -> rejection
 *  - Offered equity share < 10%         -> rejection
 *
 * The investment form is detected by the presence of its three signature
 * fields (capital_sought, valuation_pre_money, equity_share). This avoids
 * relying on a `[hidden]` shortcode, which is not part of Contact Form 7
 * core and would otherwise require an additional plugin.
 *
 * @package curixus
 */

defined( 'ABSPATH' ) || exit;

const CURIXUS_INVESTMENT_CAPITAL_MAX   = 10000000;
const CURIXUS_INVESTMENT_VALUATION_MAX = 100000000;
const CURIXUS_INVESTMENT_EQUITY_MIN    = 10;

/**
 * Check whether the current submission belongs to the investment form.
 *
 * @param array $data Posted CF7 data.
 * @return bool
 */
function curixus_is_investment_application( array $data ): bool {
	return array_key_exists( 'capital_sought', $data )
		&& array_key_exists( 'valuation_pre_money', $data )
		&& array_key_exists( 'equity_share', $data );
}

/**
 * Collect every business-rule violation in the submission.
 *
 * Returns an empty array when the application passes all checks.
 *
 * @param array $data Posted CF7 data.
 * @return string[] List of reason keys.
 */
function curixus_investment_rejection_reasons( array $data ): array {
	$capital   = isset( $data['capital_sought'] ) ? (float) $data['capital_sought'] : 0.0;
	$valuation = isset( $data['valuation_pre_money'] ) ? (float) $data['valuation_pre_money'] : 0.0;
	$equity    = isset( $data['equity_share'] ) ? (float) $data['equity_share'] : 0.0;

	$reasons = array();

	if ( $capital > CURIXUS_INVESTMENT_CAPITAL_MAX ) {
		$reasons[] = 'capital_over_limit';
	}
	if ( $valuation > CURIXUS_INVESTMENT_VALUATION_MAX ) {
		$reasons[] = 'valuation_over_limit';
	}
	if ( $equity > 0 && $equity < CURIXUS_INVESTMENT_EQUITY_MIN ) {
		$reasons[] = 'equity_below_minimum';
	}

	return $reasons;
}

/**
 * Skip the default CF7 mail when the application does not match the criteria
 * and send a rejection email to the applicant instead.
 *
 * @param bool             $skip_mail   Current skip flag.
 * @param WPCF7_ContactForm $contact_form Form instance.
 * @return bool
 */
add_filter( 'wpcf7_skip_mail', 'curixus_investment_maybe_reject', 10, 2 );
function curixus_investment_maybe_reject( $skip_mail, $contact_form ) {
	if ( ! class_exists( 'WPCF7_Submission' ) ) {
		return $skip_mail;
	}

	$submission = WPCF7_Submission::get_instance();
	if ( ! $submission ) {
		return $skip_mail;
	}

	$data = $submission->get_posted_data();
	if ( ! curixus_is_investment_application( $data ) ) {
		return $skip_mail;
	}

	$reasons = curixus_investment_rejection_reasons( $data );
	if ( empty( $reasons ) ) {
		return $skip_mail;
	}

	curixus_investment_send_rejection_email( $data, $reasons );
	return true;
}

/**
 * Build an inline-styled <img> tag with the brand logo for use in emails.
 *
 * Prefers the dark header logo (designed for dark backgrounds) and falls back
 * to the standard custom_logo. Returns the bloginfo name when no logo is set
 * so the brand area never appears empty.
 *
 * @return string
 */
function curixus_investment_email_brand_html(): string {
	$logo_id = (int) get_theme_mod( 'dark_header_logo', 0 );
	if ( ! $logo_id ) {
		$logo_id = (int) get_theme_mod( 'custom_logo', 0 );
	}

	if ( $logo_id ) {
		$logo_url = wp_get_attachment_image_url( $logo_id, 'medium' );
		if ( $logo_url ) {
			return sprintf(
				'<img src="%1$s" alt="%2$s" height="40" style="display:inline-block;height:40px;width:auto;border:0;outline:none;text-decoration:none;">',
				esc_url( $logo_url ),
				esc_attr( get_bloginfo( 'name' ) )
			);
		}
	}

	return sprintf(
		'<span style="font-size:20px;font-weight:700;color:#ffffff;letter-spacing:0.04em;">%s</span>',
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * Send a branded HTML rejection email to the applicant.
 *
 * Renders all triggered reasons — a single one is shown inline in the
 * paragraph, multiple are shown as a list so the user can fix everything
 * in one go instead of getting rejected repeatedly.
 *
 * @param array    $data    Posted CF7 data.
 * @param string[] $reasons Rejection reason keys.
 */
function curixus_investment_send_rejection_email( array $data, array $reasons ): void {
	$email = isset( $data['contact_email'] ) ? sanitize_email( $data['contact_email'] ) : '';
	if ( ! is_email( $email ) || empty( $reasons ) ) {
		return;
	}

	$reason_messages = array(
		'capital_over_limit'   => sprintf(
			'the requested investment amount exceeds the maximum ticket size we currently support (DKK %s)',
			number_format_i18n( CURIXUS_INVESTMENT_CAPITAL_MAX )
		),
		'valuation_over_limit' => sprintf(
			'the pre-money valuation is above our current investment range (maximum DKK %s)',
			number_format_i18n( CURIXUS_INVESTMENT_VALUATION_MAX )
		),
		'equity_below_minimum' => sprintf(
			'the offered equity share is below our minimum requirement of %d%%',
			CURIXUS_INVESTMENT_EQUITY_MIN
		),
	);

	$explanations = array();
	foreach ( $reasons as $reason_key ) {
		if ( isset( $reason_messages[ $reason_key ] ) ) {
			$explanations[] = $reason_messages[ $reason_key ];
		}
	}
	if ( empty( $explanations ) ) {
		$explanations[] = 'the application does not match our current investment criteria';
	}

	$company    = isset( $data['company_name'] ) ? sanitize_text_field( $data['company_name'] ) : 'your company';
	$site_host  = wp_parse_url( home_url(), PHP_URL_HOST );
	$brand_html = curixus_investment_email_brand_html();

	$body  = '<div style="margin:0;padding:24px;background:#f5f5f5;font-family:Arial,sans-serif;color:#10233A;">';
	$body .= '<div style="max-width:560px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;">';

	$body .= '<div style="padding:32px;background:#10233A;color:#ffffff;text-align:center;">';
	$body .= $brand_html;
	$body .= '</div>';

	$body .= '<div style="padding:32px;">';
	$body .= '<h1 style="margin:0 0 16px;font-size:22px;line-height:1.3;color:#10233A;">Thank you for your application</h1>';
	$body .= '<p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Hi,</p>';

	if ( 1 === count( $explanations ) ) {
		$body .= '<p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Thank you for considering Curixus. We have reviewed your application for <strong>' . esc_html( $company ) . '</strong>, and unfortunately we are unable to move forward at this stage because ' . esc_html( $explanations[0] ) . '.</p>';
	} else {
		$body .= '<p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Thank you for considering Curixus. We have reviewed your application for <strong>' . esc_html( $company ) . '</strong>, and unfortunately we are unable to move forward at this stage for the following reasons:</p>';
		$body .= '<ul style="margin:0 0 16px;padding-left:20px;font-size:15px;line-height:1.6;">';
		foreach ( $explanations as $explanation ) {
			$body .= '<li style="margin:0 0 8px;">' . esc_html( $explanation ) . '.</li>';
		}
		$body .= '</ul>';
	}

	$body .= '<p style="margin:0 0 16px;font-size:15px;line-height:1.6;">If your circumstances change in the future, you are welcome to apply again.</p>';
	$body .= '<p style="margin:24px 0 0;font-size:14px;line-height:1.6;color:#778493;">Best regards,<br>The Curixus Team</p>';
	$body .= '</div>';

	$body .= '<div style="padding:16px 32px;background:#f5f5f5;font-size:11px;line-height:1.5;color:#778493;text-align:center;">';
	$body .= 'This is an automatic message — please do not reply.';
	$body .= '</div>';

	$body .= '</div>';
	$body .= '</div>';

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: Curixus <no-reply@' . $site_host . '>',
	);

	wp_mail( $email, 'Your application to Curixus', $body, $headers );
}
