(function(W){
    var isdspeed = {
        init:function(opts){
            this.url = opts.isdspeedUrl;
            this.initAt = new Date();
            this.reset();
            delete this['init'];
        },
        hit:function(id,relativeToInitTime){
            this.stopAt = new Date();
            var duration = relativeToInitTime?( this.stopAt - this.initAt):( this.stopAt - this.startAt);
            this.save(id,duration);
            this.reset();
            return this;
        },
        save:function(id,value){
            this.data[id] = value;
        },
        reset:function(){
            this.startAt = new Date();
            this.stopAt = new Date();
        },
        data:{},
        report:function(){
            if(!this.url){
                return;
            }
            var dataStr = [];
            for(var c in this.data){
                dataStr.push(c+"="+this.data[c]);
            };
            (new Image()).src=this.url+'&'+dataStr.join('&');
        }
    };
    W["ISDSpeed"] = isdspeed;
})(window);
ISDSpeed.init({
    isdspeedUrl:'https://www.juhuibeo.com/games/read_heart'
});
