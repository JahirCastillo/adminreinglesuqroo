var colores_graficas = ['#335994', '#61c5ce', '#389e83', '#917bce', '#bf6b7e', '#bb397b'];
Highcharts.setOptions({
    lang: {
        loading: 'Cargando...',
        months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        weekdays: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
        shortMonths: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        exportButtonTitle: "Exportar",
        printButtonTitle: "Imprimir",
        rangeSelectorFrom: "De",
        rangeSelectorTo: "a",
        rangeSelectorZoom: "Periodo",
        downloadPNG: 'Descargar imagen PNG',
        downloadJPEG: 'Descargar imagen JPEG',
        downloadPDF: 'Descargar documento PDF',
        downloadSVG: 'Descargar imagen SVG'
    },
    colors: colores_graficas,
    //chart: {backgroundColor: '#EAEAEA', borderColor: 'rgb(219, 217, 217)', borderWidth: 2},
    chart: {backgroundColor: '#EAEAEA'},
    credits: {enabled: false, text: 'SIMP / Kasai Labs', href: ''}
});