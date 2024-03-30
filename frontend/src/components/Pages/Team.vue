<template>
  <div class="container">
    <div class="row justify-content-between">
      <h2 class="ml-3">Team Management</h2>
      <button class="btn btn-primary mr-5" v-if="role=='HR'" @click="showForm">
        Create a team
      </button>
    </div>
    <!-- Form to create a new team -->
    <div v-if="addForm" class="card p-3 mt-3">
      <form @submit.prevent="createTeam">
        <h3>Create a Team</h3>
        <div class="form-group">
          <label for="teamName">Team Name:</label>
          <input
            type="text"
            class="form-control"
            id="teamName"
            v-model="newTeam.name"
            placeholder="Team Name"
            required
          />
        </div>
        <div class="form-group">
          <label for="teamLeader">Team Leader:</label>
          <select
            class="form-control"
            id="teamLeader"
            v-model="newTeam.team_leader_id"
          >
            <option value="" selected disabled>Select Team Leader</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.name }}
            </option>
          </select>
        </div>
        <div class="form-group">
          <label for="employees">Employees:</label>
          <select
            class="form-control"
            id="employees"
            v-model="selectedEmployees"
            required
            multiple
            size="3"
          >
            <option value="" selected>Select one or more Employees</option>
            <option
              v-for="employee in users"
              :key="employee.id"
              :value="employee.id"
            >
              {{ employee.name }}
            </option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary">Create Team</button>
        <button @click="addForm = false" class="btn btn-secondary">
          Cancel
        </button>
      </form>
    </div>
    <!-- Display existing teams -->
    <div class="row">
      <div class="col-lg-4 col-md-6" v-for="team in teams" :key="team.id">
        <div class="card mt-3">
          <div class="card-body">
            <h5 class="card-title">{{ team.name }}</h5>
            <p class="card-text">
              <strong>Team Members: </strong>{{ team.member_count }}
            </p>
            <p class="card-text">
              Team Leader:
              <strong>
                {{ team.team_leader.name }}
              </strong>
            </p>
            <p class="card-text">
             <strong>Employees Name: </strong>
             <ol>
              <li class="text-" v-for="employee in team.employees" :key="employee.id">
              {{ employee.name }}
             </li>
            </ol>
            </p>
            <button class="btn btn-primary" v-if="role === 'HR'" @click="showDropDown(team)">
              Add Employee
            </button>
            <button
              type="button"
              class="btn icon"
              v-if="role=='HR'"
              @click="showDeleteConfirmation(team.id)"
            >
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="custom-modal" v-if="isModalOpen">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Employee</h5>
          <button type="button" class="close" @click="closeModal">
            &times;
          </button>
        </div>
        <div class="modal-body">
          <!-- Form to edit project -->
          <form @submit.prevent="assignEmployee(selectedEmployeeId)">
            <!-- Inputs for editing project details -->
            <div class="form-group">
              <select
                class="form-control my-2"
                id="employeeSelect"
                v-model="selectedEmployeeId"
              >
                <option value="">Select Employee</option>
                <option
                  v-for="employee in users"
                  :key="employee.id"
                  :value="employee.id"
                >
                  {{ employee.name }}
                </option>
              </select>
            </div>
            <!-- Buttons to save changes or close modal -->
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">
                Save Changes
              </button>
              <button
                type="button"
                class="btn btn-secondary"
                @click="closeModal"
              >
                Close
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Add this code within your template -->
    <div class="custom-modal" v-if="deleteConfirmation">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete Project</h5>
          <button type="button" class="close" @click="cancelDelete">
            &times;
          </button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this team?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" @click="confirmDelete">
            Delete
          </button>
          <button type="button" class="btn btn-secondary" @click="cancelDelete">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";

const newTeam = ref({
  teamName: "",
  team_leader_id: null,
});
const selectedEmployees = ref([]);
const teams = ref([]);
const users = ref([]);
const selectedEmployeeId = ref(""); // Reactive variable to store the selected team for which dropdown should be displayed
const selectedTeam = ref(null); // Reactive variable to store the selected employee ID
const isModalOpen = ref(false);
const addForm = ref(false);
const deleteConfirmation = ref(false);
const selectedTeamId = ref("");
const token = localStorage.getItem("token");
const role = localStorage.getItem("role");
const headers = {
  Authorization: `Bearer ${token}`,
};

const showForm = () => {
  addForm.value = true;
};

const fetchTeams = async () => {
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/teams", {
      headers,
    });
    teams.value = response.data.teams;
    console.log(response.data);
  } catch (error) {
    console.error("Error fetching teams:", error);
  }
};

const fetchUsers = async () => {
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/employees", {
      headers,
    });
    users.value = response.data.users;
    console.log(response.data);
  } catch (error) {
    console.error("Error fetching users:", error);
  }
};

const createTeam = async () => {
  try {
   const response = await axios.post(
      "http://127.0.0.1:8000/api/teams",
      {
        name: newTeam.value.name,
        team_leader_id: newTeam.value.team_leader_id,
        employee_ids: selectedEmployees.value,
      },
      {
        headers,
      }
    );
    newTeam.value = { name: "", team_leader_id: null };
    addForm.value = false;
    console.log(selectedEmployees.value);
    selectedEmployees.value = [];
    console.log("Team created:", response.data);
    fetchTeams();
  } catch (error) {
    console.error("Error creating team:", error);
  }
};

const showDropDown = (team) => {
  isModalOpen.value = true;
  selectedTeam.value = team;
};

const closeModal = () => {
  // teamToShowDropdown.value = null;
  isModalOpen.value = false;
};

const assignEmployee = async (employeeId) => {
  if (!employeeId || !selectedTeam.value) {
    return; // If no employee is selected, do nothing
  }

  const team = selectedTeam.value;

  try {
    const response = await axios.post(
      "http://127.0.0.1:8000/api/teams/assign-employee",
      { user_id: employeeId, team_id: team.id },
      { headers }
    );
    console.log(response.data);
    isModalOpen.value = false;
    selectedTeam.value = null;
    selectedEmployeeId.value = "";

    fetchTeams(); // Refresh teams list after assigning employee
  } catch (error) {
    console.error("Error assigning employee to team:", error);
  }
};

// Add this method to show the delete confirmation modal
const showDeleteConfirmation = (TeamId) => {
  selectedTeamId.value = TeamId;
  deleteConfirmation.value = true;
};
const deleteProject = async (TeamId) => {
  try {
    await axios.delete(`http://127.0.0.1:8000/api/teams/${TeamId}`, {
      headers,
    });
    teams.value = teams.value.filter((p) => p.id !== TeamId);
  } catch (error) {
    console.error("Error deleting project:", error);
  }
};
// Add these methods for confirming or canceling project deletion
const confirmDelete = async () => {
  try {
    await deleteProject(selectedTeamId.value);
    deleteConfirmation.value = false;
  } catch (error) {
    console.error("Error deleting project:", error);
  }
};

const cancelDelete = () => {
  deleteConfirmation.value = false;
};

onMounted(() => {
  fetchTeams();
  fetchUsers();
});
</script>

<style scoped>
.card {
  background-color: #babdf1;
  /* padding: 10px 10px; */
  border-radius: 10px;
  margin-bottom: 15px;
  /* align-items: center; */
  /* text-align: center; */
}

.icon {
  font-size: 18px;
}

.custom-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: #fefefe;
  padding: 5px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
  max-width: 500px; /* Adjust as needed */
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.modal-title {
  margin: 0;
}

.close {
  cursor: pointer;
}

.modal-body {
  margin-bottom: 10px;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
}
</style>
