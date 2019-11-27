<li class="nav-label">Dashboard</li>
<li class="{{ Request::is('stats*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! url('stats') !!}"><i data-feather="activity"></i><span>Stats</span></a>
</li>
<li class="{{ Request::is('myApps*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('myApps.index') !!}"><i data-feather="layers"></i><span>My Apps</span></a>
</li>
<li class="{{ Request::is('myBillings*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="#"><i data-feather="battery-charging"></i><span>My Billing</span></a>
</li>
<li class="nav-label mg-t-25">Info</li>
<li class="{{ Request::is('mails*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="#"><i data-feather="mail"></i><span>Mail</span></a>
</li>
<li class="nav-label mg-t-25">Webapp</li>
<li class="{{ Request::is('home*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! url('home') !!}"><i data-feather="box"></i><span>Products</span></a>
</li>
<li class="{{ Request::is('home/services*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="#"><i data-feather="globe"></i><span>Services</span></a>
</li>

