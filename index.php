<?php  
    require_once 'Person.php';
    require_once 'PersonList.php';

    // создание экземпляра по существующему id
    $person = new Person(1, 'Thomas', "Gold", "2002-06-27", 1, "Brest");
    // сохранение его в бд
    $person->saveToDB();

    // вызов методов из п. 3 и 4 1-го задания
    echo $person->name . " is " . Person::birthdayToAge($person1) . " years old<br/>";
    echo $person->name . ' ' . Person::getMale($person1) . "<br/>";

    // получение форматированного пользователя 
    var_dump($person->getFormattedPerson());

    // 2 задание
    $list = new PersonList('id >= 0');
    var_dump($list->select());

    //$list->deleteByIdList();