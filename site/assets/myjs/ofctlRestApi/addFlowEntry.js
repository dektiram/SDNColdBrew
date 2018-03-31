mLastData = {};
var mBrutusinForms = null;
var mJsonForm = null;
var mContainer = null;
	
var mSchemaResolver = function (names, data, cb) {
		//console.log(names);
		//console.log(data);
		//console.log(cb);
		var schemas = new Object();
		var schema = new Object();
		schema.type = "string";
        if (data.species === "dog") {
            schema.title = "Dog breed";
            schema.enum = ["bulldog", "labrador"]
        } else {
            schema.title = "Cat breed";
            schema.enum = ["siamese", "persian"]
        }
        //console.log(mSchema);
        //schemas["$.action[0].action_param"] = schema;
    	setTimeout(function(){cb(schemas)},500); // in order to show asynchrony
	};

function formValidate(){
	if (mJsonForm.validate()) {alert('Validation succeeded');}
}
function formGetData(){
	//alert(JSON.stringify(mJsonForm.getData(), null, 4));
	alert(JSON.stringify(preProcessFormData(), null, 4));
}
function formSubmit(){
	var postParam = preProcessFormData();
	//console.log(mFormUrl);
	//console.log(postParam);
	myAjaxRequest(mFormUrl,{'ofctlRestApi':postParam},{},true,'text',function(dtReply){
		console.log(dtReply);
	});
}
function preProcessFormData(){
	var retJson = mJsonForm.getData();
	
	function iterateJson(obj) {
		for (var property in obj) {
			
			if (obj.hasOwnProperty(property)) {
				if (typeof obj[property] == "object") {
					iterateJson(obj[property]);
				}else{
					//console.log(property + "   " + obj[property]);
					if(!isNaN(obj[property])){
						//console.log('numeric');
						obj[property] = Number(obj[property]);
					}else{
						var s1 = obj[property];
						if((s1.substring(0,1) == '\"') && (s1.substring(s1.length-1,s1.length) == '\"')){
							s1 = s1.substring(1,(s1.length-1));
						}
						obj[property] = s1;
					}
				}
			}
		}
	}
	iterateJson(retJson);
	return retJson;
}

$( document ).ready(function() {
	//console.log(mSchema);
	//console.log(mData);
	mBrutusinForms = brutusin["json-forms"];
	mJsonForm = mBrutusinForms.create(mSchema);
	mContainer = document.getElementById('formContainer');
	mJsonForm.render(mContainer, mData);
	mJsonForm.schemaResolver = mSchemaResolver;
	$('.btnValidate').on( 'click',  function(){
		formValidate();
	});
	$('.btnGetData').on( 'click',  function(){
		formGetData();
	});
	$('.btnSubmit').on( 'click',  function(){
		formSubmit();
	});
	
});

