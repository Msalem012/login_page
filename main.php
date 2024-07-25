<?php

abstract class Animal {
    protected $name;

    public function __construct($name) {
        $this->name = $name;
    }

    abstract public function makeSound(string $locale): string;

    public function getName(): string {
        return $this->name;
    }

    abstract public function getType(string $locale): string;
}

class Dog extends Animal {
    private $breeds = [
        'ru' => 'Лабрадор',
        'en' => 'Labrador'
    ];

    public function makeSound(string $locale): string {
        return $locale === 'ru' ? 'Гав' : 'Woof';
    }

    public function getType(string $locale): string {
        return $this->breeds[$locale];
    }
}

class Cat extends Animal {
    public function makeSound(string $locale): string {
        return $locale === 'ru' ? 'Мяу' : 'Meow';
    }

    public function getType(string $locale): string {
        return $locale === 'ru' ? 'Кошка' : 'Cat';
    }
}

class ConfigReader {
    public const LOCALE_RU = 'ru';
    public const LOCALE_EN = 'en';
}

class Controller {
    private $locale;

    public function __construct(string $locale) {
        $this->locale = $locale;
    }

    public function index() {
        $rex = new Dog('Rex');
        $murka = new Cat('Мурка');

        $output = '';
        $output .= $this->showLine($rex);
        $output .= $this->showLine($murka);

        return $output;
    }

    public function showLine(Animal $animal) {
        $type = $animal->getType($this->locale);
        $name = $animal->getName();
        $sound = $animal->makeSound($this->locale);

        $says = $this->locale === 'ru' ? 'говорит' : 'says';

        return "<strong>$type $name</strong> $says <em>$sound</em><br><br>";
    }
}

// Обработка выбора языка
$locale = isset($_POST['locale']) ? $_POST['locale'] : ConfigReader::LOCALE_RU;

$controller = new Controller($locale);
$output = $controller->index();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Animal Sounds</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        h2 {
            color: #333;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }
        .language-section {
            margin-bottom: 30px;
        }
        form {
            margin-bottom: 20px;
        }
        select, input[type="submit"] {
            font-size: 16px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
<form method="post">
    <select name="locale">
        <option value="ru" <?php echo $locale == 'ru' ? 'selected' : ''; ?>>Русский</option>
        <option value="en" <?php echo $locale == 'en' ? 'selected' : ''; ?>>English</option>
    </select>
    <input type="submit" value="Change Language">
</form>

<div class="language-section">
    <h2><?php echo $locale == 'ru' ? 'Русский' : 'English'; ?>:</h2>
    <?php echo $output; ?>
</div>
</body>
</html>