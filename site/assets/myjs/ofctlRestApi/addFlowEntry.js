var mSchemaResolver = function (names, data, cb) {
		console.log(names);
		console.log(data);
		console.log(cb);
	};
$( document ).ready(function() {
	//console.log(mSchema);
	//console.log(mData);
	var mBrutusinForms = brutusin["json-forms"];
	var mJsonForm = mBrutusinForms.create(mSchema);
	var mContainer = document.getElementById('formContainer');
	mJsonForm.render(mContainer, mData);
	mJsonForm.schemaResolver = mSchemaResolver;
});

