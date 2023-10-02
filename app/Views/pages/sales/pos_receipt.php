<?= $this->extend('template/pos_receipt') ?>
<?= $this->section('content') ?>
<div id="invoice-POS">
    <center id="top">
        <div class="info">
            <h1 class="text-uppercase"><?= $title ?? "Sales Receipt" ?></h1>
        </div>
        <div class="logo" style="background: url(<?= base_url('assets/images/logo.png') ?>) no-repeat;"></div>
        <div class="info">
            <h2 class="text-uppercase">Codewrite Technology Ltd</h2>
        </div><!--End Info-->
        <p>Address</p>
    </center><!--End InvoiceTop-->
    <div class="d-flex flex-row justify-content-between gap-1">
        <div class="info">
            <address>
                <strong>Customer</strong> : street city, state 0000</br>
                <strong>Address</strong> : JohnDoe@gmail.com</br>
                <strong>Phone</strong> : 555-555-5555</br>
            </address>
        </div>
        <div class="info">
            <address>
                <strong>Time</strong> : 01/10/23 15:34 </br>
                <strong>Reference</strong> : INV1691213202</br>
                <strong>Sale Person</strong> : Sales Manger</br>
            </address>
        </div>
    </div>
    <div id="bot">
        <div id="table">
            <table>
                <tr class="tabletitle">
                    <td class="item">
                        <h2>Item</h2>
                    </td>
                    <td class="Hours">
                        <h2>Price</h2>
                    </td>
                    <td class="Hours">
                        <h2>Qty</h2>
                    </td>
                    <td class="Rate">
                        <h2>Sub Total</h2>
                    </td>
                </tr>

                <tr class="service">
                    <td class="tableitem">
                        <p class="itemtext">Communication</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">5.00</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">5</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">$375.00</p>
                    </td>
                </tr>

                <tr class="service">
                    <td class="tableitem">
                        <p class="itemtext">Asset Gathering</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">5.00</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">3</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">$225.00</p>
                    </td>
                </tr>

                <tr class="service">
                    <td class="tableitem">
                        <p class="itemtext">Design Development</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">5.00</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">5</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">$375.00</p>
                    </td>
                </tr>
                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="Rate">
                        <h2>Tax</h2>
                    </td>
                    <td class="payment">
                        <h2>$419.25</h2>
                    </td>
                </tr>
                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="Rate">
                        <h2>Discount</h2>
                    </td>
                    <td class="payment">
                        <h2>$419.25</h2>
                    </td>
                </tr>

                <tr class="tabletitle">
                    <td></td>
                    <td></td>
                    <td class="Rate">
                        <h2>Total</h2>
                    </td>
                    <td class="payment">
                        <h2>$3,644.25</h2>
                    </td>
                </tr>

            </table>
        </div><!--End Table-->

        <div id="legalcopy">
            <p class="legal"><strong>Thank you for your business!</strong>
            </p>
        </div>

    </div><!--End InvoiceBot-->
</div><!--End Invoice-->
<?= $this->endSection() ?>