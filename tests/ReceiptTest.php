<?php
namespace TDD\Test;
require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR. 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php'; // Use autoload.php located inside vendor file - goes to folder root and navigates to file accordingly

use PHPUnit\Framework\TestCase; // uses PHPUnit TestCase
use TDD\Receipt; // Use class Receipt

class ReceiptTest extends TestCase {

    // setUp and tearDown assures that tests run independently and do not rely on each other
    public function setUp() {
        $this->Formatter = $this->getMockBuider('TDD\Formatter')
            ->setMethods(['currencyAmt'])
            ->getMock();
            $this->Formatter->expects($this->any())
                ->method('currencyAmt')
                ->with($this->anything())
                ->will($this->returnArgument(0));
        $this->Receipt = new Receipt($this->Formatter);
    }

    public function tearDown() {
        unset($this->Receipt);
    }

    // Define data provider dot block below. No spaces between @dataProvider

    /**
     * @dataProvider provideSubtotal
     */

    public function testSubtotal($items, $expected) {
        // $input = [0,2,5,8]; // what we want to display as expected answer, the value that gets tested
        $coupon = null;
        // $output = $this->Receipt->total($input, $coupon);
        // replace above code with the data provider
        $output = $this->Receipt->subtotal($items, $coupon);
        $this->assertEquals(
            $expected, // expected answer
            $output, // what the test actually outputs
            'When summing the total should equal {$expected}' // error message if expected answer != to actual output
        );
    }
    
    public function provideSubtotal() {
        return [
            'ints totaling 16' => [[1, 2, 5, 8], 16],
            [[-1, 2, 5, 8], 14],
            [[1, 2, 8], 11],
        ];
    }

    public function testTotalAndCoupon() {
        $input = [0,2,5,8]; // what we want to display as expected answer, the value that gets tested
        $coupon = 0.20;
        $output = $this->Receipt->subtotal($input, $coupon);
        $this->assertEquals(
            12, // expected answer
            $output, // what the test actually outputs
            'When summing the total should equal 12.' // error message if expected answer != to actual output
        );
    }

    // Throw an exception
    public function testSubtotalException() {
        $input = [0,2,5,8];
        $coupon = 1.20;
        $this->expectException('BadMethodCallException');
        $output = $this->Receipt->subtotal($input, $coupon);
    }

    public function testPostTaxTotal() {
        $items = [1,2,5,8];
        $tax = 0.20;
        $coupon = null;
        $Receipt = $this->getMockBuilder('TDD\Receipt')
            ->setMethods(['tax', 'subtotal'])
            ->setConstructorArgs([$this->Formatter])
            ->getMock();
        $Receipt->expects($this->once())
            ->method('subtotal')
            ->with($items, $coupon)
            ->will($this->returnValue(10.00));
        $Receipt->expects($this->once())
            ->method('tax')
            ->with(10.00)
            ->will($this->returnValue(1.00));
        $result = $Receipt->postTaxTotal([1,2,5,8], null); // items, tax percentage, and coupon (if needed)
        $this->ASSERTEQUALS(11.0, $result);
    }

    public function testTax() {
        $inputAmount = 10.00;
        $this->Receipt->tax = 0.10;
        $output = $this->Receipt->tax($inputAmount);
        $this->assertEquals(
            1.00,
            $output,
            'The tax calculation should equal 1.00.'
        );
    }

}
?>