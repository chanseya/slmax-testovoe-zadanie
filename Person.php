<?php
    class Person {
        private $id;
        public $name;
        public $surname;
        public $birthday;
        public $male;
        public $cityOfBirth;
        
        public function __construct($id, $name = null, $surname = null, $birthday = null, $male = null, $cityOfBirth = null)
        {
            try {
                $conn = new PDO("mysql:host=localhost;dbname=slmax", "root", "");
            }
            catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }

            switch(func_num_args()) {
                case 1:
                    $result = $conn->query("SELECT * FROM Person WHERE id = $id")->fetch();
                    if($result) {
                        echo "$this->name is found<br/>";
                    } else {
                        echo "User with this id is not exist<br/>";
                    }

                    $this->id = $id;
                    $this->id = $result["id"];
                    $this->name = $result["name"];
                    $this->surname = $result["surname"];
                    $this->birthday = $result["birthday"];
                    $this->male = $result["male"];
                    $this->cityOfBirth = $result["cityOfBirth"];
                    break;
                default:
                    $this->id = is_int($id) ? $id : 0;
                    $this->name = empty($name) || !is_string($name) ? "Unknown" : $name;
                    $this->surname =  empty($surname) || !is_string($surname) ? "Unknown" : $surname;
                    $this->birthday = checkdate(explode('-', $birthday)[1], explode('-', $birthday)[2], explode('-', $birthday)[0]) ? $birthday : date('Y-m-d');
                    if($male > 1)
                        $this->male = 1;
                    else if($male < 0)
                        $this->male = 0;
                    else
                        $this->male = $male;
                    $this->cityOfBirth = empty($cityOfBirth) || is_string($cityOfBirth) ? $cityOfBirth : "Unknown";
            }

            $conn = null;
        }

        public function saveToDB() {
            try {
                $conn = new PDO("mysql:host=localhost;dbname=slmax", "root", "");

                $result = $conn->exec("INSERT INTO Person (id, name, surname, birthday, male, cityOfBirth) 
                                        VALUES ($this->id, '$this->name', '$this->surname', '$this->birthday', $this->male, '$this->cityOfBirth')");

                if($result) {
                    echo "$this->name added to the person table<br/>";
                } else {
                    echo "Query is failed<br/>";
                }
            }
            catch (PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }

            $conn = null;
        }

        public function deleteFromDB() {
            try {
                $conn = new PDO("mysql:host=localhost;dbname=slmax", "root", "");

                $result = $conn->exec("DELETE FROM Person WHERE id = $this->id");

                if($result) {
                    echo "$this->name is deleted<br/>";
                } else {
                    echo "Query is failed<br/>";
                }
            }
            catch (PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }

            $conn = null;
        }

        public static function birthdayToAge($person) {
            $birthday_timestamp = strtotime($person->birthday);
            $age = date('Y') - date('Y', $birthday_timestamp);
            if (date('md', $birthday_timestamp) > date('md')) {
                $age--;
            }
            return $age;
        }

        public static function getMale($person) {
            return $person->male ? "Male" : "Female";
        }

        public function getFormattedPerson() {
            $obj = new stdClass();
            $obj->id = $this->id;
            $obj->name = $this->name;
            $obj->surname = $this->surname;
            $obj->birthday = Person::birthdayToAge($this);
            $obj->male = Person::getMale($this);
            $obj->cityOfBirth = $this->cityOfBirth;

            return $obj;
        }

    }
