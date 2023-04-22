<?php

namespace D4rk0snet\Adoption\Models;

use D4rk0snet\Adoption\Enums\AdoptedProduct;
use D4rk0snet\CoralCustomer\Model\CustomerModel;
use D4rk0snet\Coralguardian\Enums\Language;
use D4rk0snet\CoralOrder\Enums\PaymentMethod;
use D4rk0snet\CoralOrder\Enums\Project;
use Exception;
use Stripe\SetupIntent;

class AdoptionModel
{
    /**
     * @required
     */
    private CustomerModel $customerModel;

    /**
     * @required
     */
    private AdoptedProduct $adoptedProduct;

    /**
     * @required
     */
    private int $quantity;

    /**
     * @required
     */
    private float $amount;

    /**
     * @required
     */
    private Language $lang;

    /**
     * @required
     */
    private PaymentMethod $paymentMethod;

    /**
     * @required
     */
    private Project $project;

    private ?SetupIntent $stripePaymentIntent = null;

    private ?int $customAmount = null;

    private ?array $names;

    public function afterMapping()
    {
        if ($this->quantity < 1) {
            throw new Exception("Quantity can not be less than 1");
        }

        /*if ($this->amount < $this->getAdoptedProduct()->getProductPrice() * $this->getQuantity()) {
            throw new Exception("Price is below the product price");
        }*/
    }

    public function getAdoptedProduct(): AdoptedProduct
    {
        return $this->adoptedProduct;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAdoptedProduct(AdoptedProduct $adoptedProduct): AdoptionModel
    {
        $this->adoptedProduct = $adoptedProduct;
        return $this;
    }

    public function setQuantity(int $quantity): AdoptionModel
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function setAmount(int $amount): AdoptionModel
    {
        $this->amount = $amount;
        return $this;
    }

    public function getLang(): Language
    {
        return $this->lang;
    }

    public function setLang(Language $lang): AdoptionModel
    {
        $this->lang = $lang;
        return $this;
    }

    public function getPaymentMethod(): PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(PaymentMethod $paymentMethod): AdoptionModel
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getCustomerModel(): CustomerModel
    {
        return $this->customerModel;
    }

    public function setCustomerModel(CustomerModel $customerModel): AdoptionModel
    {
        $this->customerModel = $customerModel;
        return $this;
    }

    // @todo : A renommer
    public function getStripePaymentIntent(): ?SetupIntent
    {
        return $this->stripePaymentIntent;
    }

    public function setStripePaymentIntent(?SetupIntent $stripePaymentIntent): AdoptionModel
    {
        $this->stripePaymentIntent = $stripePaymentIntent;
        return $this;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     * @return AdoptionModel
     */
    public function setProject(Project $project): AdoptionModel
    {
        $this->project = $project;
        return $this;
    }

    public function getCustomAmount(): ?int
    {
        return $this->customAmount;
    }

    public function setCustomAmount(?int $customAmount): AdoptionModel
    {
        $this->customAmount = $customAmount;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getNames(): ?array
    {
        return $this->names;
    }

    /**
     * @param array|null $names
     */
    public function setNames(?array $names): AdoptionModel
    {
        $this->names = $names;

        return $this;
    }
}
