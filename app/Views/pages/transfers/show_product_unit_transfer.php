<?= $this->extend('template/default') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Unit Transfer Details</h4>
            <h6>View sale details</h6>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-sales-split">
                <h2>Unit Transfer Detail : <?= $transfer->invoice; ?></h2>
                <ul>
                    <li>
                        <a href="javascript:void(0);"></a>
                    </li>
                </ul>
            </div>
            <div class="invoice-box table-height" style="max-width: 1600px;width:100%;overflow: auto;margin:15px auto;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                <table style="width: 100%;line-height: inherit;text-align: left; display:table !important;">
                    <tbody>
                        <tr>
                            <td colspan="6" style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px;width:100%; text-align:center">

                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                        <h1>Unit Transfer Inovice</h1>
                                    </font>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px;width:100%;">
                            </td>
                            <td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;min-width: 150px !important;">
                                <div>
                                    <font style="vertical-align: inherit;margin-bottom:25px;">
                                        <font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                            Store</font>
                                    </font><br>
                                    <font style="vertical-align: inherit;margin-bottom:25px;">
                                        <font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                            Invoice Info</font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            Reference:</font>
                                    </font><br>
                                </div>
                            </td>
                            <td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;width:calc(25%)">
                                <div>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            <?= $transfer->store->name; ?> </font>
                                    </font><br>
                                    <font style="vertical-align: inherit;margin-bottom:25px;">
                                        <font style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                            &nbsp;</font>
                                    </font><br>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                            <?= $transfer->invoice; ?> </font>
                                    </font><br>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;display:table !important;">
                    <tbody>
                        <tr class="heading" style="background: #F3F2F7;">
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                From Product
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                To Product
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                From QTY
                            </td>
                            <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                To QTY
                            </td>
                        </tr>
                        <?php
                        if (isset($transfer))
                            foreach ($transfer->items as $key => $row) : ?>
                            <tr class="details" style="border-bottom:1px solid #E9ECEF;">
                                <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                    <?= $row->fromProduct->image_uri
                                        ? '<a class="product-img"><img src="' . base_url($row->fromProduct->image_uri) . '" alt="product"></a>'
                                        : '<a class="p-3"></a>' ?>
                                    <a target="_blank" href="<?= site_url('products/' . $row->from_product_id) ?>">
                                        <?= setting('App.ShowProductSKU') === 'yes' ? $row->product->sku : '' ?> <?= $row->fromProduct->name ?> (<?= $row->fromProduct->unit->label ?>)
                                </td>
                                <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                    <?= $row->toProduct->image_uri
                                        ? '<a class="product-img"><img src="' . base_url($row->toProduct->image_uri) . '" alt="product"></a>'
                                        : '<a class="p-3"></a>' ?>
                                    <a target="_blank" href="<?= site_url('products/' . $row->to_product_id) ?>">
                                        <?= setting('App.ShowProductSKU') === 'yes' ? $row->toProduct->sku : '' ?> <?= $row->toProduct->name ?> (<?= $row->toProduct->unit->label ?>)
                                </td>
                                <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; "><?= $row->from_unit_qty ?></td>
                                <td style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; "><?= $row->to_unit_qty ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="row">

                <div class="col-lg-12">
                    <a href="javascript:void(0);" class="btn btn-submit me-2">Print Invoice</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>