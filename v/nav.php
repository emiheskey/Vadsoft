<!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->

          <!-- search form -->
          <!-- <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form> -->
          <!-- /.search form -->

          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>


            <?php if($_SESSION['role'] == "app_admin") { ?>
              <li class="active treeview">
                <a href="./">
                  <i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-files-o"></i>
                  <span>Organization setup</span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="addorganization"><i class="fa fa-circle-o"></i> Add new</a></li>
                  <li><a href="organizationcategory"><i class="fa fa-circle-o"></i> Setup category</a></li>
                  <li><a href="organization?mode=edit"><i class="fa fa-circle-o"></i> View organization</a></li>
                </ul>
              </li>


            <?php } else { ?>

            

              <li class="active treeview">
                <a href="./">
                  <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
              </li>
              
              <li>
                <a href="createsupervisors">
                  <i class="fa fa-th"></i> <span>Create Supervisor</span>
                </a>
              </li>
              <li>
                <a href="#">
                  <i class="fa fa-th"></i> <span>Assign Supervisor</span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="assignstafftosupervisors?mode=staff"><i class="fa fa-circle-o"></i> Staff</a></li>
                  <li><a href="assignstafftosupervisors?mode=unit"><i class="fa fa-circle-o"></i> Unit</a></li>
                  <li><a href="assignstafftosupervisors?mode=department"><i class="fa fa-circle-o"></i> Department</a></li>
                  <li><a href="assignstafftosupervisors?mode=project"><i class="fa fa-circle-o"></i> Project</a></li>
                </ul>
              </li>

              <li class="treeview">
                <a href="#">
                  <i class="fa fa-files-o"></i>
                  <span>Organization setup</span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="organization?mode=edit"><i class="fa fa-circle-o"></i> View organization</a></li>
                </ul>
              </li>

              <li>
                <a href="department">
                  <i class="fa fa-th"></i> <span>Department</span>
                </a>
              </li>


              <li class="treeview">
                <a href="#">
                  <i class="fa fa-table"></i>
                  <span>Unit</span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="unit"><i class="fa fa-circle-o"></i> Setup units</a></li>
                  <li><a href="assignunit"><i class="fa fa-circle-o"></i> Assign unit to staff</a></li>
                  <li><a href="unit?mode=listonly"><i class="fa fa-circle-o"></i> Unit list</a></li>
                </ul>
              </li>

              <li class="treeview">
                <a href="#">
                  <i class="fa fa-th"></i> <span>Staff</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="staff"><i class="fa fa-circle-o"></i> Add new staff</a></li>
                  <li><a href="staff?mode=listonly"><i class="fa fa-circle-o"></i> Staff list</a></li>
                </ul>
              </li>

              <li class="treeview">
                <a href="#">
                  <i class="fa fa-th"></i> <span>Project</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="addproject"><i class="fa fa-circle-o"></i> Add new project</a></li>
                  <li><a href="addproject?mode=listonly"><i class="fa fa-circle-o"></i> Project list</a></li>
                </ul>
              </li>

              <li class="treeview">
                <a href="#">
                  <i class="fa fa-laptop"></i>
                  <span>Assesment parameters</span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="assesmenttype"><i class="fa fa-circle-o"></i> Assesment type</a></li>
                  <li><a href="assesmentkpi"><i class="fa fa-circle-o"></i> Key Performance Index</a></li>
                  <li><a href="assesmentmeasure"><i class="fa fa-circle-o"></i> Assesment category</a></li>
                </ul>
              </li>

              <li class="treeview">
                <a href="#">
                  <i class="fa fa-laptop"></i>
                  <span>Measurement parameters</span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <!-- <li><a href="assesmentvaluesetup?unit=1"><i class="fa fa-circle-o"></i> Staff in units</a></li> -->
                  <li><a href="assesmentvaluesetup?unit=3"><i class="fa fa-circle-o"></i> Staff</a></li>
                  <li><a href="assesmentvaluesetup?unit=4"><i class="fa fa-circle-o"></i> Units</a></li>
                  <li><a href="assesmentvaluesetup?unit=6"><i class="fa fa-circle-o"></i> Department</a></li>
                  <li><a href="assesmentvaluesetup?unit=5"><i class="fa fa-circle-o"></i> Project</a></li>
                </ul>
              </li>

            <?php } ?>



            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
