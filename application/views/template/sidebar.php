<script>
    $(document).ready(function() {
        if ($('#linkActive').hasClass("active")) {
            $('#components-nav').addClass("show");
        }
    });
</script>


<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Query Menu -->

        <?php

        $role_id = $this->session->userdata('role_id');
        $query_menu = "SELECT distinct b.menu_id as id, a.menu from user_menu a 
        join user_access_menu b on a.id = b.menu_id where b.role_id = $role_id 
        order by b.menu_id ASC ";

        $menu = $this->db->query($query_menu)->result_array();
        ?>

        <!-- looping menu -->

        <?php foreach ($menu as $m) : ?>
            <li class="nav-heading"><?= $m['menu'] ?></li>

            <!-- sub menu sesuai menu -->
            <?php
            $menuId = $m['id'];
            // $query_sub_menu = "SELECT a.* from user_sub_menu a join user_menu b on a.menu_id = b.id where a.menu_id = $menuId and a.is_active = 1";
            $query_sub_menu = "SELECT DISTINCT a.sub_menu_id, b.* from user_access_menu a inner join 
            user_sub_menu b on a.sub_menu_id = b.id 
            inner join user_menu c on c.id = a.menu_id 
            where a.role_id = $role_id and a.menu_id = $menuId and b.is_active = 1 and b.sub_menu_id = 0
            order by b.id ";

            $subMenu = $this->db->query($query_sub_menu)->result_array();
            ?>

            <!-- looping sub menu -->

            <?php foreach ($subMenu as $sm) : ?>

                <?php
                $subMenuId = $sm['id'];
                $queryParent = "SELECT DISTINCT a.sub_menu_id, b.* from user_access_menu a inner join 
                user_sub_menu b on a.sub_menu_id = b.id 
                inner join user_menu c on c.id = a.menu_id 
                where a.role_id = $role_id and b.sub_menu_id = $subMenuId and b.is_active = 1 
                order by b.id";

                $parent = $this->db->query($queryParent);
                ?>

                <?php if ($parent->num_rows() > 0) : ?>
                    <li class="nav-item" data-bs-target="#components-nav" data-bs-toggle="collapse">
                        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="<?= $sm['url'] ?>">
                            <i class="<?= $sm['icon'] ?>"></i><span><?= $sm['title'] ?></span><i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

                            <?php foreach ($parent->result() as $p) : ?>
                                <li>
                                    <?php if ($title == $p->title) : ?>
                                        <a href="<?= base_url($p->url) ?>" id="linkActive" class="active">
                                        <?php else : ?>
                                            <a href="<?= base_url($p->url) ?>">
                                            <?php endif; ?>
                                            <i class="<?= $p->icon ?>"></i><span><?= $p->title ?></span>
                                            </a>
                                </li>

                            <?php endforeach; ?>

                            <?php echo "</ul></li>" ?>

                        <?php else : ?>

                            <li class="nav-item ">
                                <?php if ($title == $sm['title']) : ?>
                                    <a class="nav-link active " href="<?= base_url($sm['url']) ?>">
                                    <?php else : ?>
                                        <a class="nav-link collapsed " href="<?= base_url($sm['url']) ?>">
                                        <?php endif; ?>
                                        <i class="<?= $sm['icon'] ?> "></i>
                                        <span><?= $sm['title'] ?></span>
                                        </a>
                            </li>

                        <?php endif; ?>

                    <?php endforeach; ?>


                <?php endforeach; ?>






                <li class="nav-item">
                    <a class="nav-link collapsed" href="<?= base_url('auth/logout') ?>">
                        <i class="bi bi-box-arrow-in-left"></i>
                        <span>Logout</span>
                    </a>
                </li><!-- End Login Page Nav -->



                        </ul>

</aside><!-- End Sidebar-->