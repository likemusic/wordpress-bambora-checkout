<?php

namespace Likemusic\Wordpress\Bambora\Checkout\Core;

class BamboraCheckoutCorePlugin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'addAdminMenu']);
    }

    public function getOptionKey()
    {
        return 'bambora-checkout-core';
    }

    public function addAdminMenu(): void
    {
        add_menu_page('Bambora.com Payment', 'Bambora.com Payment', 'manage_options',
            'bambora-checkout-core', [$this, 'adminPageProcessor'], 'dashicons-money-alt'
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
        ];

        $this->updateOption($newConfig);
    }

    private function updateOption($newConfig)
    {
        update_option($this->getOptionKey(), $newConfig);
    }

    private function getOption()
    {
        return get_option($this->getOptionKey());
    }

    private function renderAdminPage(): void
    {
        $config = $this->getOption();

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
