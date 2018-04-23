<script type="text/javascript" src="<?php print base_url().'assets/'; ?>myjs/tdbfFunctionCollection.js"></script>
<link href="<?php print base_url().'assets/'; ?>plugins/jsoneditor/dist/jsoneditor.min.css" rel="stylesheet" type="text/css">
<script src="<?php print base_url().'assets/'; ?>plugins/jsoneditor/dist/jsoneditor.min.js"></script>
<?php
print $_SERVER['SERVER_ADDR'];
?>
<div id="editorContainer"></div>

<script type="text/javascript">
        // create the editor
        var container = document.getElementById("editorContainer");
        var options = {
    modes: ['text', 'code', 'tree', 'form', 'view'],
    mode: 'code',
    ace: ace
  };
        var editor = new JSONEditor(container, options);

        // set json
        var json = {
            "Array": [1, 2, 3],
            "Boolean": true,
            "Null": null,
            "Number": 123,
            "Object": {"a": "b", "c": "d"},
            "String": "Hello World"
        };
        editor.set(json);

        // get json
        var json = editor.get();
    </script>