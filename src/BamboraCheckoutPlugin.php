<?php

namespace Likemusic\Wordpress\Bambora\Checkout;

use JetBrains\PhpStorm\NoReturn;
use Likemusic\BamboraCheckout\Parameters\LinkParameters;

class BamboraCheckoutPlugin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('parse_request', [$this, 'parseRequestHandler']);
    }

    public function parseRequestHandler(): void
    {
        $requestedPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        $config = get_option('bambora');

        $paymentUrl = $config['payment_url'];

        if ($requestedPath !== $paymentUrl) {
            return;
        }

        $knownParams = $this->getKnownParams($_REQUEST);

        $paymentUrlGenerator = new ConfiguredPaymentUrlGenerator();
        $paymentUrl = $paymentUrlGenerator->makeByArray($knownParams);

        $this->redirect($paymentUrl);

        exit();
    }

    private function getKnownParams(array $arr): array
    {
        $knownParametersNames = LinkParameters::getNames();

        return array_intersect_key($arr, array_fill_keys($knownParametersNames, null));
    }


    #[NoReturn]
    private function redirect($url): void
    {
        $this->redirectByLocation($url);
        $this->renderRedirectPage($url);
    }

    #[NoReturn]
    private function redirectByLocation($url, $permanent = false): void
    {
        header('Location: ' . $url, true, $permanent ? 301 : 302);
    }

    private function renderRedirectPage($url): void
    {
        $escapesUrl = htmlspecialchars($url);

        $jsonUrl = json_encode($url);

        echo "<meta http-equiv=\"refresh\" content=\"0;url={$escapesUrl}\">";

        echo "<script>window.location.href={$jsonUrl};</script>";
        echo "<noscript>If you will not redirect to payment page please go to link: <a href=\"{$escapesUrl}\">{$url}</a></noscript>";
    }

    public function addAdminMenu(): void
    {
        add_menu_page('Bambora.com Payment', 'Bambora.com Payment', 'manage_options',
            'bambora', [$this, 'adminPageProcessor'], 'dashicons-money-alt'
        );
    }

    public function adminPageProcessor(): void
    {
        $this->processPost();
        $this->renderAdminPage();
    }

    private function processPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $newConfig = [
            'merchant_id' => $_POST['merchant_id'],
            'hash_key' => $_POST['hash_key'],
            'payment_url' => $_POST['payment_url']
        ];

        update_option('bambora', $newConfig);
    }

    private function renderAdminPage(): void
    {
        $config = get_option('bambora');

        echo <<<CONTENT
<div class="wrap">
    <h1>Bambora.com Payment Settings</h1>
    <form method="post">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label for="merchant_id">Merchant ID</label></th>
                    <td>
                        <input name="merchant_id" type="text" id="merchant_id" value="{$config['merchant_id']}" class="regular-text">
                        <p class="description" id="merchant_id-description">Go to <a href="https://web.na.bambora.com/">Bambora's member area</a> to get it.</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="hash_key">Hash Key</label></th>
                    <td>
                        <input name="hash_key" type="text" id="hash_key" value="{$config['hash_key']}" class="regular-text">
                        <p class="description" id="hash_key-description">Go to <a href="https://web.na.bambora.com/">Bambora's member area</a> to get it.</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="payment_url">Payment URL</label></th>
                    <td>
                        <input name="payment_url" type="text" id="payment_url" value="{$config['payment_url']}" class="regular-text">
                        <p class="description" id="payment_url-description">Url where given request parameters would be used to generate and redirect to payment form. Go to <a href="https://dev.na.bambora.com/docs/references/checkout/">Link parameters docs</a> to see all available parameters.</p>
                    </td>
                </tr>
            </tbody>
        </table>
CONTENT;
        submit_button(__('Update'), 'primary large', 'save', false);

        echo <<<CONTENT
</form>
</div>
CONTENT;
    }
}
