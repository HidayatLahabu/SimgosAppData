function n(t){if(!t&&t!==0||isNaN(t))return"0";const r=new Intl.NumberFormat("id-ID").format(Math.abs(t));return t<0?`(${r})`:r}export{n as f};
