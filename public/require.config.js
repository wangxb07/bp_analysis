var components = {
    "packages": [
        {
            "name": "bootstrap",
            "main": "bootstrap-built.js"
        },
        {
            "name": "jquery",
            "main": "jquery-built.js"
        },
        {
            "name": "bootstrap-datepicker",
            "main": "bootstrap-datepicker-built.js"
        }
    ],
    "shim": {
        "bootstrap": {
            "deps": [
                "jquery"
            ]
        },
        'bootstrap-datepicker': {
            "deps": ["jquery"]
        }
    },
    "baseUrl": public_path,
};
if (typeof require !== "undefined" && require.config) {
    require.config(components);
} else {
    var require = components;
}
if (typeof exports !== "undefined" && typeof module !== "undefined") {
    module.exports = components;
}
