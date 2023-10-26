<?= $this->extend('template/import') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4><?= isset($title) ? $title : 'Import Product'; ?></h4>
            <h6>Import categories and products</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h1>Upload an excel file to convert into JSON</h1>
            <input type="file" id="file_upload" />
            <button onclick="upload()">Upload</button>
            <br>
            <br>
            <!-- container to display the json result -->
            <textarea id="json-result" style="display:none;height:500px;width:350px;"></textarea>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
    // Method to upload a valid excel file
    function upload() {
        var files = document.getElementById('file_upload').files;
        if (files.length == 0) {
            alert("Please choose any file...");
            return;
        }
        var filename = files[0].name;
        var extension = filename.substring(filename.lastIndexOf(".")).toUpperCase();
        if (extension == '.XLS' || extension == '.XLSX' || extension == '.CSV') {
            excelFileToJSON(files[0]);
        } else {
            alert("Please select a valid excel/csv file.");
        }
    }

    //Method to read excel file and convert it into JSON 
    function excelFileToJSON(file) {
        try {
            var reader = new FileReader();
            reader.readAsBinaryString(file);
            reader.onload = function(e) {

                var data = e.target.result;
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });
                var result = {};
                workbook.SheetNames.forEach(function(sheetName) {
                    var roa = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                    if (roa.length > 0) {
                        result[sheetName] = roa;
                    }
                });
                //displaying the json result
                var resultEle = document.getElementById("json-result");
                resultEle.value = JSON.stringify(result, null, 4);
                resultEle.style.display = 'block';
            }
        } catch (e) {
            console.error(e);
        }
    }
</script>
<?= $this->endSection() ?>