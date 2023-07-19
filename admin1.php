<?php
include_once('functions-page.php');
$name = $_SESSION['Admin_name'];

if (!isset($name)){
    header("location: login.php");
}

?>   
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
   <title>Admin page</title>
   <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
   <link rel="stylesheet" href="css1/style.css">
   </head>
   <body>

     <input type="checkbox" id="nav-toggle">

    <div class="sidebar">
	<div class="sidebar-brand">
	<i class="fas fa-users"></i>
	</div>

	 <div class="sidebar-menu">
	 <ul>
	 <li>
	 <a href="admin1.php" class="active"><span class="lab la-igloo"></span>
	 <span>Admin Dashboard</span></a>
	 </li>
	 <li>
	 <a href="logged.php"><span class="las la-users"></span>
	 <span>Shop</span></a>
	 </li>
	 <li>
	 <a href="products.php"><span class="las la-clipboard-list"></span>
	 <span>Products</span></a>
	 </li>
	
	 <li>
	 <a href="user.php"><span class="las la-user-circle"></span>
	 <span>Accounts</span></a>
	 </li>
	 <li>
	 <a href=""><span class="las x  clipboard-list"></span>
	 <span>Tasks</span></a>
	 </li>
	 </ul>
	 </div>
	</div>

     <div class="main-content">
       <header1>
         <h1>

           <label for="nav-toggle">
             <span class="las la-bars"></span>
           </label>
           Dashboard
         </h1>

         <div class="search-wrapper">
           <span class="las la-search"></span>
           <input type="search" placeholder="Search here" />
         </div>

         <div class="user-wrapper">
           <img src="malome.jpg" width="400%" alt="">
               <h4>YouRUncle</h4>
               <small>Super Admin</small>
             </div>
           </div>
       </header>

       <main>

         <div class="cards">
           <div class="card-single">
             <div>
               <h1>54</h1>
               <span>Customers</span>
             </div>
             <div>
               <span class="las la-users"></span>
             </div>
           </div>



           <div class="cards">
             <div class="card-single">
               <div>
                 <h1>79</h1>
                 <span>Projects</span>
               </div>
               <div>
                 <span class="las la-clipboard"></span>
               </div>
             </div>


             <div class="cards">
               <div class="card-single">
                 <div>
                   <h1>124</h1>
                   <span>Orders</span>
                 </div>
                 <div>
                   <span class="las la-shopping-bag"></span>
                 </div>
               </div>


               <div class="cards">
                 <div class="card-single">
                   <div>
                     <h1>$6k</h1>
                     <span>Income</span>
                   </div>
                   <div>
                     <span class="lab la-google-wallet"></span>
                   </div>
                 </div>
               </div>

               <di class="recent-grid">
                 <div class="projects">
                   <div class="card">
                     <div class="card-header">
                       <h2>Recent Products</h2>

                       <button href="products.php">
                         See all<span class="las las-arrow-right"></span>
                       </button>
                     </div>
                     <div class="card-body">
                       <table width="100%">
                         <thead>
                           <tr>
                             <td>Project title</td>
                             <td>Department</td>
                             <td>Status</td>
                           </tr>
                         </thead>
                         <tbody>
                           <tr>
                             <td>UI/UX Design</td>
                             <td>UI Team</td>
                             <td>
                               <span class="status purple"></span>
                               review
                             </td>
                           </tr>
                           <tr>
                             <td>web development</td>  
                             <td>Front-end</td>
                             <td>
                               <span class="status pink"></span>
                               in progress
                             </td>
                           </tr>
                           <tr>
                             <td>Ushop app</td>
                             <td>Mobile Teams</td>
                             <td>
                               <span class="status orange"></span>
                               pending
                             </td>
                           </tr>
                         </tbody>
                       </table>
                     </div>
                   </div>
                 </div>

                 <div class="customers">
                   <div class="card">
                     <div class="card-header">
                       <h3>New Customer</h3>

                       <button>
                         See all<span class="las la-arrow-right"></span>
                       </button>
                     </div>

                     <div class="card-body">
                       <div class="customer">
                         <div class="info">
                           <img src="images/malome.jpg" width="40px" height="40px" alt="">
                             <div>
                               <h4>Lewis S. Cunningham</h4>
                               <small>CEO Experpt</small>
                             </div>
                         </div>
                       </div>
                       <span class="las la-user-circle"></span>
                       <span class="las la-comment"></span>
                       <span class="las la-phone"></span>
                     </div>
                   </div>
                 </div>
               </di>
             </main>
     </div>
          

	</body>
	</html>