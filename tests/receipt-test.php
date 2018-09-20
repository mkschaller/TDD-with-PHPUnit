<?php
namespace TDD\Test;
require dirname(dirname(__FILE__)) . DIRECTOR_SEPERATOR . 'vendor' . DIRECTORY_SEPERATOR . 'autoload.php';

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase {
    public function testTotal() {
        $Receipt = new Receipt();

    }
}
?>