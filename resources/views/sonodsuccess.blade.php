
    <style>
      body {
        text-align: center;
        padding: 40px 0;
        background: #EBF0F5;
      }
        h1 {
          color: #88B04B;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-weight: 900;
          font-size: 40px;
          margin-bottom: 10px;
        }
        p {
          color: #404F5E;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-size:20px;
          margin: 0;
        }
      i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left:-15px;
      }
      .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
      }

      .buttons{
        color: white;
        text-decoration: none;
        padding: 8px 14px;
        border-radius: 7px;
        margin: 20px 8px;
      }
      .buttons.d{
        background: #0b4fb6;
      }
      .buttons.r{
        background: #8d2407;
      }
      .buttons.h{
        background: #380bb6;
      }

    </style>

      <div class="card">
      <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
        <i class="checkmark">✓</i>
      </div>
        <h1>ধন্যবাদ</h1>
        <p>আমারা আপনার পেমেন্ট গ্রহন করেছি!</p>
        <div style="display:flex">
            <a class="buttons d" target="_blank" href="{{ url('/sonod/d/'.$sonod->id) }}">আপনার সনদটি ডাউনলোড করুন</a>
            <a class="buttons r" target="_blank" href="{{ url('/invoice/d/'.$sonod->id) }}">আপনার রশিদটি ডাউনলোড করুন</a>
            </div>
            <a class="buttons h" href="/">মুল পেজএ ফিরে যান</a>

      </div>
