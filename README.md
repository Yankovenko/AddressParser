#Usage
```php
require 'src/AddressParser.php';

$str = 'ул. Строителей 69-18  654005';
$res = \Yankovenko\utils\AddressParser::parse($str);

print_r ($res);
/* results
Array
(
    [index] => 654005
    [house] => 69
    [room] => 18
    [street] => ул. Строителей
)
*/
```
