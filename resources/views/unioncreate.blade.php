

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<div class="container">

<form action="{{ url('/unionCreate') }}" method="POST">
    @csrf

    <div class="row">

        <div class="form-group col-md-6">
            <label for="">full_name</label>
            <input type="text" class="form-control" name="full_name" value="৩নং তেঁতুলিয়া ইউনিয়ন পরিষদ">
        </div>

        <div class="form-group col-md-6">
            <label for="">short_name_e</label>
            <input type="text" class="form-control" name="short_name_e" value="tepriganj">
        </div>

        <div class="form-group col-md-6">
            <label for="">short_name_b</label>
            <input type="text" class="form-control" name="short_name_b" value="তেঁতুলিয়া">
        </div>

        <div class="form-group col-md-6">
            <label for="">thana</label>
            <input type="text" class="form-control" name="thana" value="তেঁতুলিয়া">
        </div>

        <div class="form-group col-md-6">
            <label for="">district</label>
            <input type="text" class="form-control" name="district" value="পঞ্চগড়">
        </div>

        <div class="form-group col-md-6">
            <label for="">defaultColor</label>
            <input type="text" class="form-control" name="defaultColor" value="green">
        </div>

        <div class="form-group col-md-6">
            <label for="">payment_type</label>
            <input type="text" class="form-control" name="payment_type" value="Postpaid">
        </div>

        <div class="form-group col-md-6">
            <label for="">status</label>
            <input type="text" class="form-control" name="status" value="active">
        </div>

    </div>



    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $.ajax({
        url: 'https://premium36.web-hosting.com:2083/cpsess0351997767/frontend/paper_lantern/subdomain/doadddomain.html?domain=demo2&rootdomain=uniontax.gov.bd&dir=demo2.uniontax.gov.bd&go=Create',
        type: "get",
        async: true,
        data: { },
        success: function (data) {
           console.log(data)
        },

    });

</script>
