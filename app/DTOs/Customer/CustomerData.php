<?php

namespace App\DTOs\Customer;


class CustomerData
{
    public ?string $first_name;
    public ?string $last_name;
    public ?string $voter_key;
    public int $type_person;
    public ?string $rfc;
    public ?string $address;
    public ?int $zone_id;
    public ?string $reference;
    public ?int $customer_type_id;
    public ?int $use_of_type_id;
    public ?int $service_type_id;
    public ?int $service_status_id;
    public ?int $classification_type_id;
    public ?bool $drainage_use;
    public ?string $email;
    public ?string $int_num;
    public ?string $ext_num;
    public ?string $geolocation;
    public ?string $phone;
    public ?string $url_image;
    public ?int $additional_observation_id;
    public ?string $meter;
    public ?string $storage_capacity;
    public ?string $installation_date;

    public function __construct(array $data)
    {
        $this->first_name = $data['first_name'] ?? null;
        $this->last_name = $data['last_name'] ?? null;
        $this->voter_key = $data['voter_key'] ?? null;
        $this->type_person = $data['type_person'] ?? 1;
        $this->rfc = $data['rfc'] ?? null;
        $this->address = $data['address'] ?? null;
        $this->zone_id = $data['zone_id'] ?? null;
        $this->reference = $data['reference'] ?? null;
        $this->customer_type_id = $data['customer_type_id'] ?? null;
        $this->use_of_type_id = $data['use_of_type_id'] ?? null;
        $this->service_type_id = $data['service_type_id'] ?? null;
        $this->service_status_id = $data['service_status_id'] ?? 1;
        $this->classification_type_id = $data['classification_type_id'] ?? null;
        $this->drainage_use = $data['drainage_use'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->int_num = $data['int_num'] ?? null;
        $this->ext_num = $data['ext_num'] ?? null;
        $this->geolocation = $data['geolocation'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->url_image = $data['url_image'] ?? null;
        $this->additional_observation_id = $data['additional_observation_id'] ?? null;
        $this->meter = $data['meter'] ?? null;
        $this->storage_capacity = $data['storage_capacity'] ?? null;
        $this->installation_date = $data['installation_date'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'voter_key' => $this->voter_key,
            'rfc' => $this->rfc,
            'type_person' => $this->type_person,
            'address' => $this->address,
            'zone_id' => $this->zone_id,
            'reference' => $this->reference,
            'customer_type_id' => $this->customer_type_id,
            'use_of_type_id' => $this->use_of_type_id,
            'service_type_id' => $this->service_type_id,
            'service_status_id' => $this->service_status_id,
            'classification_type_id' => $this->classification_type_id,
            'drainage_use' => $this->drainage_use,
            'email' => $this->email,
            'int_num' => $this->int_num,
            'ext_num' => $this->ext_num,
            'geolocation' => $this->geolocation,
            'phone' => $this->phone,
            'url_image' => $this->url_image,
            'additional_observation_id' => $this->additional_observation_id,
            'meter' => $this->meter,
            'storage_capacity' => $this->storage_capacity,
            'installation_date' => $this->installation_date,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}
