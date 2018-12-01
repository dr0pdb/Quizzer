<nav class="navbar navbar-default navbar-fixed-top" style="margin-bottom: 60px;">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../"><img src="/images/quiz.png" height="50px" style="margin: -10px;" alt="Quizzer" /></a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="/">Home</a></li>
                <?php if($loginInfo != 0) { ?>
                    <li><a href="/quizzes">My Quizzes</a></li>
                    <?php if($isInstructor || $isAdmin) { ?>
                        <li><a href="/organize">Organize a Quiz</a></li>
                    <?php } ?>
                    <?php if($isStudent) { ?>
                        <li><a href="/choose">Take a Quiz</a></li>
                    <?php } ?>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if($loginInfo == 0) { ?>
                    <li ><a id = "register-link" href = "/register" ><button style = "margin-top: -7px; margin-bottom: -7px; margin-right: -20px""  class="btn btn-primary" >Register</button ></a ></li >
                    <li ><a id = "signin-link" href = "/signin" ><button style = "margin-top: -7px; margin-bottom: -7px;" class="btn btn-info" >Sign In</button ></a ></li >
                <?php } else { ?>
                    <li ><a id = "logout-link" href = "/profile" ><button style = "margin-top: -7px; margin-bottom: -7px; margin-right: -20px"  class="btn btn-info" >Profile</button ></a ></li >
                    <li ><a id = "logout-link" href = "/logout" ><button style = "margin-top: -7px; margin-bottom: -7px;"  class="btn btn-danger" >Logout</button ></a ></li >
                <?php } ?>
            </ul>
        </div>
    </div>
    <div id="progress-bar" class="progress" style="display:none;margin: 0px; padding: 0px">
        <div class="indeterminate"></div>
    </div>
</nav>