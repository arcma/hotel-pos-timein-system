function buildCss(baseURL) {
    var css = "<style>";
    css += "@font-face{";
    css += "font-family: AvenirNextPForBBG;";
    css += "src: url('"+baseURL+"assets/fonts/AvenirNextPForBBG-Regular.otf') format('opentype');";
    css += "}";
    css += "@font-face{";
    css += "font-family: AvenirNextPForBBGBold;";
    css += "src: url('"+baseURL+"assets/fonts/AvenirNextPForBBG-Bold.otf') format('opentype');";
    css += "}";
    css += "@page{";
    css += "size: 20mm 30mm; margin: 0; size: landscape;";
    css += "}";
    css += "body{";
    css += "font-size: 10pt; font-family: AvenirNextPForBBG; color: black;"; // padding-top adjusts the spacing above all the text. font-size adjusts font size. font-family adjusts font. font-weight (400/600) adjusts normal or bold.
    css += "}";
    css += "p{";
    css += "margin: 0mm; padding: 0; margin-left: 0mm;"; // padding-top adjusts the spacing above all the text. font-size adjusts font size. font-family adjusts font. font-weight (400/600) adjusts normal or bold.
    css += "}";
    css += ".qrcode{";
    css += "width: 18mm; height: 18mm; margin-left: 0mm; margin-bottom:6mm; padding: 1px 1px 1px 1px; border: 2px black solid;";
    css += "}";
    css += "</style>";
    return css;
}

function buildInfo(data) {
    // console.log(data);
    var info = "<div style='margin: 50px;'>";
    if(data.name.length > 10) {
        info += "<p style='font-size:30pt; font-family: AvenirNextPForBBGBold;'>" + data.name + "</p>";
    } else {
        info += "<p style='font-size:40pt; font-family: AvenirNextPForBBGBold;'>" + data.name + "</p>";
    }
    if(data.aux.length > 10) {
        info += "<p style='font-size:30pt;margin-bottom:2mm;font-weight:normal;'>" + data.tag + "</p>";
    } else {
        info += "<p style='font-size:40pt;margin-bottom:2mm;font-weight:normal;'>" + data.tag + "</p>";
    }
    // if(data.aux2.length > 10) {
    //     info += "<p style='font-size:12pt;margin-bottom:3mm;'>" + data.aux2 + "</p>";
    // } else {
    //     info += "<p style='font-size:16pt;margin-bottom:3mm;'>" + data.aux2 + "</p>";
    // }
    // info += "<img src='" + data.qrcode + "' class='qrcode' />";
    info += "</div>";
    return info;
}
