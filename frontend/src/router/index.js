import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import AboutView from "../views/AboutView.vue";
import CareerView from "../views/CareerView.vue";
import SignUp from "@/components/SignUp.vue";
import Login from "@/components/Login.vue";
import Profile from "../components/Pages/Profile.vue";
import Dashboard from "@/components/Pages/Dashboard.vue";
import Task from "@/components/Pages/Task.vue";
import Employee from "@/components/Pages/Employee.vue";
import Project from "@/components/Pages/Project.vue";
import Team from "@/components/Pages/Team.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
      children: [
        {
          path: "",
          name: "dashboard",
          component: Dashboard,
          meta: { requiresAuth: true, },
        },
        {
          path: "profile",
          name: "profile",
          component: Profile,
          meta: { requiresAuth: true },
        },
        {
          path: "employees",
          name: "employees",
          component: Employee,
          meta: { requiresAuth: true, requiresRole: "HR" },
        },
        {
          path: "teams",
          name: "teams",
          component: Team,
          meta: { requiresAuth: true, },
        },
        {
          path: "projects",
          name: "projects",
          component: Project,
          meta: { requiresAuth: true },
        }
      ],
    },
    {
      path: "/login",
      name: "login",
      component: Login,
    },
    {
      path: "/signup",
      name: "signup",
      component: SignUp,
    },
    {
      path: "/about",
      name: "about",
      component: AboutView,
    },
    {
      path: "/careers",
      name: "careers",
      component: CareerView,
    },
  ],
});

// Navigation guards
router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem("token");
  // console.log(isAuthenticated);
  const userRole = localStorage.getItem("role");

  // Check if the route requires authentication
  if (to.meta.requiresAuth) {
    // Check if the user is authenticated
    if (!isAuthenticated) {
      // Redirect to the login page if not authenticated
      next({ name: "login" });
    } else {
      // Check if the route requires specific role and user has that role
      if (to.meta.requiresRole && userRole !== to.meta.requiresRole) {
        // Redirect to unauthorized page or show a message
        // For now, let's just redirect to the home page
        next({ name: "home" });
      } else {
        // Continue to the route if authenticated and has required role
        next();
      }
    }
  } else {
    // Continue to the route if it doesn't require authentication
    next();
  }
});

export default router;
