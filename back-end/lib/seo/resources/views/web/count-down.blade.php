<div style="color: rgb(0, 0, 0);margin-bottom: 1em;">
  <input type="hidden" id="code-string" value="{{ base64_encode(base64_encode(base64_encode(base64_encode(base64_encode($adsItem->code))))) }}">

  <button style="
    background: rgb(237, 28, 36);
    border: 1px solid rgb(255, 255, 255);
    color: rgb(255, 255, 255);
    font-weight: 700;
    font-size: 17px;
    border-radius: 7px;
    padding: 10px 20px;
    min-width: 170px;
    line-height: 20px;
    "
   id="traffic-countdown">Lấy mã <span id="time-down"></span></button>
  <input type="hidden" id="time-waiting" value="{{ setting('time_waiting', 60) }}"/>
</div>
