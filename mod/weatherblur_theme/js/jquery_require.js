/**
 * Well, wb.api requires jquery 1.8.3 but probably not, probably would be jsut
 * fine at 9+
 * datatables requires 9.
 * this is needed (i hope) to define() out jquery as 1.9 before require pulls
 * it in in wb.api. I hope.
 */

define("jquery", [], function() { return jQuery; });
