<head>
    <meta http-equiv="content-language" content="tr">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<style>
    *{
  font-family: arial;
  margin: 0; padding: 0;
  outline: none;
}

input::-moz-placeholder {
  color: white;
}
input:-ms-input-placeholder {
  color: white;
}
input::-webkit-input-placeholder {
  color: white;
}

body{
  display: flex;
  flex-direction: olumn;
  align-items: center;
  justify-content: center;
  height: 100vh;
  overflow: hidden;
  background-image: radial-gradient(circle at 50% -20.71%, #0098ba 0, #0097c8 5%, #0096d5 10%, #0095e0 15%, #0093e9 20%, #0090f0 25%, #008cf5 30%, #0088f8 35%, #0084f8 40%, #007ef6 45%, #3c78f2 50%, #6871ec 55%, #8669e3 60%, #9d61d9 65%, #b159cd 70%, #c050c0 75%, #cd47b2 80%, #d840a3 85%, #df3993 90%, #e43683 95%, #e73573 100%);
  transition: all .5s;
  animation: bg-anm 60s infinite;
}

@keyframes bg-anm {
  0%{
    background-image: radial-gradient(circle at 50% -20.71%, #0098ba 0, #0097c8 5%, #0096d5 10%, #0095e0 15%, #0093e9 20%, #0090f0 25%, #008cf5 30%, #0088f8 35%, #0084f8 40%, #007ef6 45%, #3c78f2 50%, #6871ec 55%, #8669e3 60%, #9d61d9 65%, #b159cd 70%, #c050c0 75%, #cd47b2 80%, #d840a3 85%, #df3993 90%, #e43683 95%, #e73573 100%);
  }

  10%{
    background-image: radial-gradient(circle at 45% -20.71%, #0098ba 0, #0097c8 5%, #0096d5 10%, #0095e0 15%, #0093e9 20%, #0090f0 25%, #008cf5 30%, #0088f8 35%, #0084f8 40%, #007ef6 45%, #3c78f2 50%, #6871ec 55%, #8669e3 60%, #9d61d9 65%, #b159cd 70%, #c050c0 75%, #cd47b2 80%, #d840a3 85%, #df3993 90%, #e43683 95%, #e73573 100%);
  }

  20%{
    background-image: radial-gradient(circle at 40% -20.71%, #0098ba 0, #0097c8 5%, #0096d5 10%, #0095e0 15%, #0093e9 20%, #0090f0 25%, #008cf5 30%, #0088f8 35%, #0084f8 40%, #007ef6 45%, #3c78f2 50%, #6871ec 55%, #8669e3 60%, #9d61d9 65%, #b159cd 70%, #c050c0 75%, #cd47b2 80%, #d840a3 85%, #df3993 90%, #e43683 95%, #e73573 100%);
  }

  30%{
    background-image: radial-gradient(circle at 35% -20.71%, #0098ba 0, #0097c8 5%, #0096d5 10%, #0095e0 15%, #0093e9 20%, #0090f0 25%, #008cf5 30%, #0088f8 35%, #0084f8 40%, #007ef6 45%, #3c78f2 50%, #6871ec 55%, #8669e3 60%, #9d61d9 65%, #b159cd 70%, #c050c0 75%, #cd47b2 80%, #d840a3 85%, #df3993 90%, #e43683 95%, #e73573 100%);
  }

  40%{
    background-image: radial-gradient(circle at 30% -20.71%, #0098ba 0, #0097c8 5%, #0096d5 10%, #0095e0 15%, #0093e9 20%, #0090f0 25%, #008cf5 30%, #0088f8 35%, #0084f8 40%, #007ef6 45%, #3c78f2 50%, #6871ec 55%, #8669e3 60%, #9d61d9 65%, #b159cd 70%, #c050c0 75%, #cd47b2 80%, #d840a3 85%, #df3993 90%, #e43683 95%, #e73573 100%);
  }

  50%{
    background-image: radial-gradient(circle at 25% -20.71%, #0098ba 0, #0097c8 5%, #0096d5 10%, #0095e0 15%, #0093e9 20%, #0090f0 25%, #008cf5 30%, #0088f8 35%, #0084f8 40%, #007ef6 45%, #3c78f2 50%, #6871ec 55%, #8669e3 60%, #9d61d9 65%, #b159cd 70%, #c050c0 75%, #cd47b2 80%, #d840a3 85%, #df3993 90%, #e43683 95%, #e73573 100%);
  }

  60%{
    background-image: radial-gradient(circle at 20% -20.71%, #0098ba 0, #0097c8 5%, #0096d5 10%, #0095e0 15%, #0093e9 20%, #0090f0 25%, #008cf5 30%, #0088f8 35%, #0084f8 40%, #007ef6 45%, #3c78f2 50%, #6871ec 55%, #8669e3 60%, #9d61d9 65%, #b159cd 70%, #c050c0 75%, #cd47b2 80%, #d840a3 85%, #df3993 90%, #e43683 95%, #e73573 100%);
  }

  70%{
    background-image: radial-gradient(circle at 15% -20.71%, #0098ba 0, #0097c8 5%, #0096d5 10%, #0095e0 15%, #0093e9 20%, #0090f0 25%, #008cf5 30%, #0088f8 35%, #0084f8 40%, #007ef6 45%, #3c78f2 50%, #6871ec 55%, #8669e3 60%, #9d61d9 65%, #b159cd 70%, #c050c0 75%, #cd47b2 80%, #d840a3 85%, #df3993 90%, #e43683 95%, #e73573 100%);
  }

  80%{
    background-image: radial-gradient(circle at 20% -20.71%, #0098ba 0, #0097c8 5%, #0096d5 10%, #0095e0 15%, #0093e9 20%, #0090f0 25%, #008cf5 30%, #0088f8 35%, #0084f8 40%, #007ef6 45%, #3c78f2 50%, #6871ec 55%, #8669e3 60%, #9d61d9 65%, #b159cd 70%, #c050c0 75%, #cd47b2 80%, #d840a3 85%, #df3993 90%, #e43683 95%, #e73573 100%);
  }

  100%{
    background-image: radial-gradient(circle at 50% -20.71%, #0098ba 0, #0097c8 5%, #0096d5 10%, #0095e0 15%, #0093e9 20%, #0090f0 25%, #008cf5 30%, #0088f8 35%, #0084f8 40%, #007ef6 45%, #3c78f2 50%, #6871ec 55%, #8669e3 60%, #9d61d9 65%, #b159cd 70%, #c050c0 75%, #cd47b2 80%, #d840a3 85%, #df3993 90%, #e43683 95%, #e73573 100%);
  }
}

.container{
  padding: 40px;
  background: #ffffff15;
  border-radius: 10px;
  transition: all .5s;
  transform: scale(.3);
  animation: container 1s forwards;
}

@keyframes container{
  from{transform: scale(.3);}
  to{transform: scale(1);}
}

.user{
  text-align: center;
  position: relative;
}

.img-group{
  text-align: center !important;
}

img{
  width: 120px;
  margin-bottom: -10px;
  text-align: center;
  transition: all .5s;
}

img:hover{transform: scale(1.1);}

.img-1{width: 120px; animation: img1 13s infinite; animation-delay: 3s;}
.img-2{width: 100px; margin-bottom: 20px; display: none;}
.img-3{width: 120px; display: none; animation: img1 13s infinite; animation-delay: 0s;}
.img-4{width: 100px; margin-bottom: 20px; display: none;}

@keyframes img1{
  0%{transform: scale(1)}
  10%{transform: scale(1.1)}
  50%{transform: scale(1) rotate(-10deg);}
  60%{transform: scale(1) rotate(5deg);}
  70%{transform: rotate(0);}
  90%{transform: scale(1.1)}
  100%{transform: scale(1)}
}

input{
  padding: 5px;
  background-color: transparent;
  color: white;
  border: none;
}

.name, .password{
  color: white;
  border-radius: 20px;
  border: 1px solid #ffffff22;
  backdrop-filter: blur(100px);
  padding: 5px 15px;
  margin: 7px;
  transition: all .5s;
}

.name:hover, .password:hover{
  border: 1px dotted #ffffff88;
}

.button{
  margin: 0 auto;
  text-align: center;
  position: relative;
  transition: all .5s;
}

button{
  padding: 10px 15px;
  border: 1px solid #ffffff11;
  background: #ffffff11;
  color: white;
  width: 100px;
  text-align: center;
  cursor: pointer;
  border-radius: 20px;
  position: relative;
  overflow: hidden;
  transition: all .5s;
}

button:hover{
  border: 1px solid #ffffff22;
  background: #ffffff33;
}

button::before{
  content: '';
  position: absolute;
  top: 0;
  left: -10px;
  width: 5px;
  height: 40px;
  background: #ffffff33;
  transition: all .5s;
  transition-delay: .2s;
}

.button:hover button::before{
  left: 100px;
  width: 20px;
  background-color: white;
}

@media screen and (max-width: 370px) {
  .container{
    padding: 40px 20px;
  }
}

@media screen and (max-width: 300px) {
  .name, .password{
    padding: 5px 10px;
  }
  .bi{display: none;}
}

@media screen and (max-width: 270px) {
  img{
    width: 100px !important;
  }
}
</style>

<div class="container">
  <div class="user">
    <div class="img-group">
      <img src="https://i.hizliresim.com/h8hx8o4.png" alt="Welcome Back" class="img-1">
      <img src="https://i.hizliresim.com/owqdcfp.png" alt="Welcome Back" class="img-2">
      <img src="https://i.hizliresim.com/js3kci3.png" alt="Welcome Back" class="img-3">
      <img src="https://i.hizliresim.com/rfuga6m.png" alt="Welcome Back" class="img-4">
    </div>
  </div>
  <form action="/login" method="POST">
    @csrf
    <div class="name">
      <i class="bi bi-person-fill"></i>
      <input type="text" placeholder="Enter Email" class="name-input" name="email" required>
    </div>
    <div class="password">
      <i class="bi bi-key-fill"></i>
      <input type="password" placeholder="Enter Password" class="pass-input" name="password" required>
    </div>
    <div class="button">
      <button>Login</button>
    </div>
  </form>
</div>

<script>
    var nameInput = document.querySelector(".name-input");
var passInput = document.querySelector(".pass-input");
var nameDiv = document.querySelector(".name");
var passDiv = document.querySelector(".password");

var passControl = document.querySelector(".pass-control");
var passOn = document.querySelector(".bi-eye");
var passOff = document.querySelector(".bi-eye-slash");

var img1 = document.querySelector(".img-1");
var img2 = document.querySelector(".img-2");
var img3 = document.querySelector(".img-3");
var img4 = document.querySelector(".img-4");

nameInput.addEventListener("click", function(){
  img3.style.display="none";
  img1.style.display="inline";
})

passInput.addEventListener("click", function(){
  img1.style.display="none";
  img3.style.display="inline";
})
</script>
