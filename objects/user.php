<?php

class User {
    public $id;
    public $email;
    public $usertype;
    public $patient;  // Holds a Patiënt object if user is a patiënt
    public $tandarts; // Holds a Tandarts object if user is a tandarts

    public function __construct($id, $email, $usertype, $extraData = null) {
        $this->id = $id;
        $this->email = $email;
        $this->usertype = $usertype;

        // Automatically assign properties based on the usertype
        if ($usertype == 'Patiënt' && $extraData) {
            $this->patient = new Patiënt(
                $extraData['PatiëntID'],
                $extraData['Naam'],
                $extraData['Geboortedatum'],
                $extraData['Telefoonnummer'],
                $extraData['Adres']
            );
        } elseif ($usertype == 'Tandarts' && $extraData) {
            $this->tandarts = new Tandarts(
                $extraData['tandartsID'],
                $extraData['Naam'],
                $extraData['Geboortedarum'],
                $extraData['Telefoonnummer'],
                $extraData['Adres'],
                $extraData['Beoordeling'],
                $extraData['Specialisaties'],
                $extraData['Beschrijving']
            );
        }
    }

    // Additional methods can be added here as needed
}

class Patiënt {
    public $patientID;
    public $name;
    public $birthdate;
    public $phone;
    public $address;

    public function __construct($patientID, $name, $birthdate, $phone, $address) {
        $this->patientID = $patientID;
        $this->name = $name;
        $this->birthdate = $birthdate;
        $this->phone = $phone;
        $this->address = $address;
    }
}

class Tandarts {
    public $tandartsID;
    public $name;
    public $birthdate;
    public $phone;
    public $address;
    public $rating;
    public $specializations;
    public $description;

    public function __construct($tandartsID, $name, $birthdate, $phone, $address, $rating, $specializations, $description) {
        $this->tandartsID = $tandartsID;
        $this->name = $name;
        $this->birthdate = $birthdate;
        $this->phone = $phone;
        $this->address = $address;
        $this->rating = $rating;
        $this->specializations = $specializations;
        $this->description = $description;
    }
}
