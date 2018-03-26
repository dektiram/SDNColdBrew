if ("undefined" === typeof brutusin || "undefined" === typeof brutusin["json-forms"]) {
    throw new Error("brutusin-json-forms-bootstrap.js requires brutusin-json-forms.js");
}
(function () {
    var BrutusinForms = brutusin["json-forms"];

    BrutusinForms.messages = {
        "validationError": "Error de validación",
        "required": "Este campo es **obligatorio**",
        "invalidValue": "Valor inválido",
        "addpropNameExistent": "Esta propiedad ya existe en el objeto",
        "addpropNameRequired": "Un nombre es obligatorio",
        "minItems": "Se requiere un mínimo de `{0}` elementos",
        "maxItems": "Se admiten a lo sumo `{0}` elementos",
        "pattern": "El valor no cumple el patrón: `{0}`",
        "minLength": "El valor debe tener **como mínimo** `{0}` caracteres de longitud",
        "maxLength": "El valor debe tener **como máximo** `{0}` caracteres de longitud",
        "multipleOf": "El valor debe ser **múltiplo de** `{0}`",
        "minimum": "El valor debe ser **mayor o igual que** `{0}`",
        "exclusiveMinimum": "El valor debe ser **mayor que** `{0}`",
        "maximum": "El valor debe ser **menor o igual que** `{0}`",
        "exclusiveMaximum": "El valor debe ser **menor que** `{0}`",
        "minProperties": "Se requieren como mínimo `{0}` propiedades",
        "maxProperties": "Se admiten a lo sumo `{0}` propiedades",
        "uniqueItems": "Los elementos del array deben ser diferentes",
        "addItem": "Añadir elemento",
        "true": "Verdadero",
        "false": "Falso"
    };
}());