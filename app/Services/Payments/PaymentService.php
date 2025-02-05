<?php

namespace App\Services\Payments;

use Illuminate\Http\Request;

abstract class PaymentService
{

    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->initializeSettings();
    }

    /**
     * Initialize the settings for the payment service.
     *
     * @return void
     */
    abstract protected function initializeSettings();

    /**
     * Create an invoice for the given order.
     *
     * @param mixed $order
     * @return void
     */
    abstract public function createCharge($order, $token);

    /**
     * prepare the data for the charge.
     *
     * @param mixed $order
     * @param string $token
     * @return void
     */
    abstract public function prepareChargeData($order, $token);
    /**
     * Retrieves the details of a charge that was previously created
     *
     * @param string $chargeId
     * @return void
     */
    abstract public function retrieveCharge($chargeId);

    /**
     * Handle the callback from the payment service.
     *
     * @return void
     */
    abstract public function callback(Request $request);

    /**
     * Process a payment for the given order.
     *
     * @param float $amount
     * @return void
     */
    abstract public function pay($order);

    /**
     * Refund a payment for the given order.
     *
     * @param float $amount
     * @return void
     */
    abstract public function refund($order);

    /**
     * Cancel a payment for the given order.
     *
     * @param float $amount
     * @return void
     */
    abstract public function cancel($order);

    /**
     * Get the status of a payment for the given order.
     *
     * @param float $amount
     * @return void
     */
    abstract public function status($order);

    /**
     * Common method to validate the order.
     *
     * @param mixed $order
     * @return bool
     */
    protected function validateOrder($order)
    {
        // Implement validation logic here
        return true; // Placeholder
    }

    /**
     * A method to get the country dial code
     * @param $coutnry
     * @return integer
     */
    public function getCountryCode($country = null)
    {
        if ($country == null)
        {
            return 965;
        }
        $countries = json_decode(file_get_contents(base_path('resources/json/country-code-dials.json')));
        foreach ($countries as $countryItem) {
            if ($countryItem['name']  == $country) {
                return str_replace('+', '', $countryItem['dial_code']);
            }
        }

        return 965;
    }
}
