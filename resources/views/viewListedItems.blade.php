<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>List an Item</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
    rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
  <!-- FilePond core -->
  <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
  <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

  <!-- FilePond image plugins -->
  <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
    rel="stylesheet" />
  <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
  <script
    src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
  <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
  <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>


  <!-- cropper.js :P -->
  <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
  <script src="https://unpkg.com/cropperjs/dist/cropper.js"></script>
  <!-- Sortable.js for drag-and-drop ordering -->
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  @vite('resources/css/viewListedItems.css')
  @vite('resources/js/listAnItem.js')
</head>

<body>
  <!-- nav bar -->
  <div class="navBar">
    <div class="navBarLeft"><img src="{{ asset('img/logo.svg') }}" alt="" /></div>

    <!-- <div class="navBarMiddle">
        <div class="searchBtnImg"><img id="magnifying"class="searchBtn" src="{{ asset('img/search-icon.svg') }}" alt="" /></div>
        <div class="searchInput"><input id="searchInput" class="search" type="text" placeholder="search" /></div>
      </div> -->

    <div class="navBarRight">
      <img class="hover" src="{{ asset('img/help.png') }}" alt="" />
      <div class="dropdown-container">
        <img class="hover notificationBtn" src="{{ asset('img/notif.png') }}" alt="" />
        <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
          <div class="notification-header">
            <h3>Notifications</h3>
          </div>
          <div class="notification-list">
            @if ($notifications->isEmpty())
            <p style="padding-left:10px;">No notifications</p>
            @else
            @foreach ($notifications as $notification)
            <div class="notification">
              <div class="title">
                <h1>
                  @if($notification['title'] === "Product Approved")
                  <span style="color:Green;">{{ $notification['title'] }}</span>
                  @elseif($notification['title'] === "Product Rejected")
                  <span style="color:red;">{{ $notification['title'] }}</span>
                  @else
                  {{ $notification['title'] }}
                  @endif
                </h1>
              </div>
              <div class="Message">{{ $notification['message'] }}</div>
              <div class="time">{{ $notification['time_ago'] }}</div>
            </div>
            @endforeach
            @endif
          </div>
        </div>
      </div>
      <img class="hover" src="{{ asset('img/wishlist.png') }}" alt="" />
      <img class="hover" src="{{ asset('img/cart.png') }}" alt="" />
      <div class="dropdown-container">
        <img class="hover profileBtn" src="{{ asset('storage/users-avatar/' . Auth::user()->avatar) }}" alt="" />
        <div class="profile-dropdown" id="profileDropdown" style="display: none;">
          <ul>
            <li><a href="">My Profile</a></li>
            <li><a href=" ">Wishlist</a></li>
            <li><a href="">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- nav bar -->
<div class="container">
  @if(session('alreadyExists'))
    <div class="alert alert-danger" style="color:red;">
      {{ session('alreadyExists') }}
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger" style="color:red;">
      {{ session('error') }}
    </div>
  @endif
  @if(session('success'))
    <div class="alert alert-danger" style="color:green;">
      {{ session('success') }}
    </div>
  @endif

  <form action="{{ route('products.sirRoss') }}" enctype="multipart/form-data" method="POST">
    @csrf
    <input type="file" name="myfile">
    <button type="submit">Submit</button>
  </form>
<div class="listHolder">
    <?php
    $myFile=fopen("FileForProduct/".date('M_Y').".txt", "r") or die("Unable to open file!");
    echo "<pre>"; 
    while(!feof($myFile)){
        $line = fgets($myFile);
        if(trim($line) !== '') {
            echo rtrim($line). "<br>"; 
        }
    }
    echo "</pre>";
    fclose($myFile);
    ?>
</div>
</div>
</body>

</html>