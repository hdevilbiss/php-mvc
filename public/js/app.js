// Custom Method, "validPassword"
$.validator.addMethod('validPassword',
function(value,element,param) {
    
    // Check for empty value
    if (value!='') {
        // Password value must contain at least 1 char
        if (value.match(/.*[a-z]+.*/i)==null) {
            return false;//FAIL
        }

        // Password value must contain at least 1 digit
        if (value.match(/.*\d+.*/)==null) {
            return false;//FAIL
        }
    }
    return true;//PASS
},
'Must contain at least one letter and one number.'
);