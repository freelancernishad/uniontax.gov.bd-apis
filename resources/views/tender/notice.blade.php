
<html>
<head>
<style>
    @page {
        size: auto;
        odd-header-name: html_myHeader1;
        even-header-name: html_myHeader1;
        odd-footer-name: html_myFooter1;
        even-footer-name: html_myFooter1;


    }
    body{
        border:2px solid black !important;
    }


</style>
</head>

<body>


    <div style="width:800px; padding:20px; border: 10px solid #787878">
        <div style="width:750px;  padding:20px; border: 5px solid #11083a;position:relative;overflow: hidden; ">



    <htmlpageheader name="myHeader1" style="display:none">
        <div style="text-align: right; border-bottom: 1px solid #000000; font-weight: bold; font-size: 10pt;">
            My document
        </div>
    </htmlpageheader>


    Hello<pagebreak>
    Hello<pagebreak>
    Hello<pagebreak>
    Hello<pagebreak>
    Hello<pagebreak>




    <htmlpagefooter name="myFooter1" style="display:none">
        <table width="100%">
            <tr>
                <td width="33%">
                    <span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span>
                </td>
                <td width="33%" align="center" style="font-weight: bold; font-style: italic;">
                    {PAGENO}/{nbpg}
                </td>
                <td width="33%" style="text-align: right;">
                    My document
                </td>
            </tr>
        </table>
    </htmlpagefooter>



    </div>
    </div>


</body>
</html>
