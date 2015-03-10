/**
 * Larachet js
 */
var Larachet=function(t){this.events=new Array,this.url=t};Larachet.prototype={watch:function(t,n){var e=new ab.Session(this.url,function(){e.subscribe(t,n)},null,{skipSubprotocolCheck:!0})}};
