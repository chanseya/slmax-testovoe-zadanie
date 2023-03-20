<?php
    require_once 'Person.php';

    if(!class_exists('Person')) {
        //Для версии php 8.0 и выше. Я пишу на 7.2, поэтому выведу через echo
        //throw new Exception('Functionality cannot be implemented without a Person class');
        echo 'Functionality cannot be implemented without a Person class';
        return 0;
    }

    class PersonList {
        public $idList;

        public function __construct($field = null, $condition = null, $value = null)
        {
            

            try {
                $conn = new PDO("mysql:host=localhost;dbname=slmax", "root", "");

                $sql = $field != null ? "SELECT id FROM Person WHERE $field $condition '$value'" : "SELECT id FROM Person";

                $result = $conn->query($sql);

                while($row = $result->fetch()) {
                    $this->idList[] = $row["id"];
                }

                $str = empty($this->idList) ? "List is empy<br/>" : "Got id of all users<br/>";
                echo $str;
            }
            catch (PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }

            $conn = null;
        }

        public function select() {
            try {
                $conn = new PDO("mysql:host=localhost;dbname=slmax", "root", "");

                $range = $this->idList != null ? implode(", ", $this->idList) : null;

                $result = $conn->query("SELECT * FROM `Person` WHERE id IN ($range)");

                if($result != false) {
                    while($row = $result->fetch()) {
                        $personList[] = new Person($row['id'], $row['name'], $row['surname'], $row['birthday'], $row['male'], $row['cityOfBirth']);
                    }
                } else {
                    echo 'List is empty<br/>';
                }

                return $personList;
            }
            catch (PDOException $e) {
                echo "Database error: " . $e->getMessage();
                return false;
            }

            $conn = null;
        }

        public function deleteByIdList() {
            foreach($this->select() as $person) {
                $person->deleteFromDB();
            }
        }
    }