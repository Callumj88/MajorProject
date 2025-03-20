<?php



    /**
     * Get the business contact information
     *
     * @param string $key
     * @return string|null
     */
function businessContact($key) {
        $contacts = [
            'phone'        => '(856) 492-7602',
            'email'        => 'andreasanchez@gmail.com',
            'address'      => 'Unit 16 Vale Supplier, Park, Resolven, SA11 4SR',
            'openingTimes' => 'Mon - Sat: 9:00 - 18:00',
        ];
        return $contacts[$key] ?? null;
    }



    