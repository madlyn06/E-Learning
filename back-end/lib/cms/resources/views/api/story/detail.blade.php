<div className="" style="display: flex; justify-content: center; align-items: center;">
  <div className="col-xl-3 col-lg-3 m-b30">
    <a href="/stories/{{ $story->slug }}" style="position: relative; display: inline-block;">
      <aside className="side-bar sticky-top">
        <div className="widget widget_archive style-1">
          <div className="story" style="position: relative;">
            <img width="285px" height="427px" style="border-radius: 8px;" src="{{$story->image}}" alt="{{$story->name}}" className="story-image" />
            <div className="story-caption" style="position: absolute; top: 58%; color: #fff; padding: 10px; ">
              <p className="title">{{$story->name}}</p>
              <p className="subtitle">{{$story->description}}<br />{{$story->created_at}}</p>
            </div>
          </div>
        </div>
      </aside>
    </a>
  </div>
</div>
<br/>
