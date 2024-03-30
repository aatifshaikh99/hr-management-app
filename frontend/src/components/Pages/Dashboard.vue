<template>
  <div class="container mt-4">
    <h2>Dashboard</h2>
    <div class="row">
      <div class="col-md-12" v-if="role != 'HR'">
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title">Employee Dashboard</h5>
            <p class="card-text">
              Welcome to the Employee Dashboard. Here you can view your profile,
              check your tasks, and access other employee-related features.
            </p>
            <!-- Add any other employee-specific information or links here -->
          </div>
        </div>
      </div>
      <div class="col-md-12" v-if="role == 'HR'">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">HR Dashboard</h5>
            <p class="card-text">
              Welcome to the HR Dashboard. Here you can manage employee
              profiles, assign tasks, and access other HR-related features.
            </p>
            <!-- Add any other HR-specific information or links here -->
          </div>
        </div>
      </div>
    </div>
    <div class="row" v-if="role == 'HR'">
      <div class="col-md-4">
        <RouterLink :to="{ name: 'projects' }" class="nav-link">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Total Projects</h5>
              <p class="card-text">{{ dashboard.project_count }}</p>
            </div>
          </div>
        </RouterLink>
      </div>
      <div class="col-md-4">
        <RouterLink :to="{ name: 'teams' }" class="nav-link">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Total Teams</h5>
              <p class="card-text">{{ dashboard.team_count }}</p>
            </div>
          </div>
        </RouterLink>
      </div>
      <div class="col-md-4">
        <RouterLink :to="{ name: 'employees' }" class="nav-link">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Total Employees</h5>
              <p class="card-text">{{ dashboard.employee_count }}</p>
            </div>
          </div>
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";

const role = localStorage.getItem("role");
const token = localStorage.getItem("token");
const headers = {
  Authorization: `Bearer ${token}`,
};

const dashboard = ref({});

onMounted(() => {
  fetchDashboardStats();
});

const fetchDashboardStats = async () => {
  try {
    const response = await axios.get(
      "http://127.0.0.1:8000/api/dashboard-stats",
      {
        headers,
      }
    );
    dashboard.value = response.data;
    console.log(dashboard.value);
  } catch (error) {
    console.error("Error fetching employees:", error);
  }
};
</script>

<style scoped>
/* Add your custom styles here */
.card {
  background-color: #babdf1; /* Light gray background */
  padding: 5px 15px;
  border-radius: 10px;
  margin-bottom: 10px;
  /* align-items: center; */
  text-align: center;
}

.nav-link {
  color: #131212;
  padding: 15px 25px;
}

.nav-link:hover {
  color: #3f16c6;
}
</style>
