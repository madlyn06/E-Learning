<h2>New Membership Registration</h2>
<p>A new membership has been registered:</p>
<ul>
    <li>Name: {{ $membership->name }}</li>
    <li>Price: {{ $membership->price }}</li>
    <li>Expire Month: {{ $membership->expire_month }}</li>
    <li>Popular: {{ $membership->is_popular ? 'Yes' : 'No' }}</li>
    <li>Active: {{ $membership->is_active ? 'Yes' : 'No' }}</li>
</ul>
