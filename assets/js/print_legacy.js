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
    css += "size: 54mm 86mm; margin: 0; size: portrait;";
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
    var info = "<div style='margin: 0;'>";
    if(data.name.length > 10) {
        info += "<p style='font-size:15pt; font-family: AvenirNextPForBBGBold;'>" + data.name + "</p>";
    } else {
        info += "<p style='font-size:22pt; font-family: AvenirNextPForBBGBold;'>" + data.name + "</p>";
    }
    if(data.aux.length > 10) {
        info += "<p style='font-size:15pt;margin-bottom:2mm;font-weight:normal;'>" + data.aux + "</p>";
    } else {
        info += "<p style='font-size:22pt;margin-bottom:2mm;font-weight:normal;'>" + data.aux + "</p>";
    }
    if(data.aux2.length > 10) {
        info += "<p style='font-size:12pt;margin-bottom:3mm;'>" + data.aux2 + "</p>";
    } else {
        info += "<p style='font-size:16pt;margin-bottom:3mm;'>" + data.aux2 + "</p>";
    }
    info += "<img src='" + data.qrcode + "' class='qrcode' />";
    info += "</div>";
    return info;
}

function convertFontArrToStyle(fonts) {
    const fontFormats = {
        'ttf': 'truetype',
        'otf': 'opentype',
    };
    let fontsStyle = '';

    _.each(fonts, (font) => {
        const fontExt = _.get(font, 'fileName', _.get(font, 'url', '')).split('.').pop().toLowerCase();
        const fontFormat = _.get(fontFormats, fontExt)
        fontsStyle += `
            @font-face{
                font-family: ${ _.get(font, 'familyName', '') };
                src: url("${ _.get(font, 'url', '') }")${ fontFormat ? ` format("${ fontFormat }")` : '' };
            }
        `;
    });

    return fontsStyle;
}

function getDefaultScripts(params) {
    return `
        <script type="text/javascript" src="${ _.get(params, 'base_url', '') }assets/js/jquery-3.7.0.min.js"); ?>"></script>
    `;
}

function getDefaultStyles(params) {
    $borderStyles = `
        .layout-container {
            border: 1px solid blue;
        }

        .layout-container div {
            border: 1px solid red;
        }

        .layout-container span {
            border: 1px solid green;
        }
    `;

    return `
        <style>
            ${ convertFontArrToStyle(_.get(params, 'config.fonts', [])) }

            @page{
                size: ${ _.get(params, 'config.printWidth', '4in') } ${ _.get(params, 'config.printHeight', '6in') };
                margin: 0;
            }
            * {
                box-sizing: border-box;
            }
            body{
                font-size: 10pt;
                color: black;
                margin: 0;
            }
            .layout-container {
                display: inline-block;
                width: ${ _.get(params, 'config.printWidth', '4in') };
                height: ${ _.get(params, 'config.printHeight', '6in') };
            }

            ${ _.get(params, 'show_borders', false) ? $borderStyles : '' }
        </style>
    `;
}

function startPrint(params) {
    callback = typeof _.get(params, 'callback') === 'function' ? _.get(params, 'callback') : function(){};

    // get template
    var templateElements = $('<div/>').append(_.unescape(_.get(params, 'template', '')));

    // get styles from template
    var styles = templateElements.find('style').clone();
    templateElements.find('style').remove();
    stylesHtml = $('<div/>').append(styles).html();

    // get scripts from template
    var scripts = templateElements.find('script').clone();
    templateElements.find('script').remove();
    scriptsHtml = $('<div/>').append(scripts).html();

    // get layout from template
    var printLayoutHtml = templateElements.html();

    // open new window and load the template
    var mywindow = window.open('', 'my div');
    mywindow.document.write(`
        <html>
            <head>
                <title></title>
                ${ getDefaultScripts(params) }
                ${ getDefaultStyles(params) }
                ${ stylesHtml }
            </head>
            <body>
                ${ printLayoutHtml }
            </body>
            ${ scriptsHtml }
        </html>
    `);

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10

    mywindow.onload = function() {
        mywindow.print();
        mywindow.close();
        callback();
    };
}
