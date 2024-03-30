<template>
  <div class="container">
    <div class="row justify-content-between">
      <h2 class="ml-3">Projects</h2>
      <button v-if="isHR == 'HR'" @click="showAddForm" class="btn add">
        <i class="fa fa-plus-circle"></i>
      </button>
    </div>
    <div v-if="addProjectForm" class="card">
      <h2>Add New Project</h2>
      <form @submit.prevent="addProject">
        <div class="form-group">
          <label for="name">Name:</label>
          <input
            type="text"
            class="form-control"
            v-model="newProject.name"
            placeholder="Project Name"
            required
          />
        </div>
        <div class="form-group">
          <label for="details">Details:</label>
          <textarea
            class="form-control"
            v-model="newProject.details"
            placeholder="Details of Projects..."
            required
          ></textarea>
        </div>
        <div class="form-group">
          <label for="status">Status:</label>
          <select class="form-control" v-model="newProject.status">
            <option value="" disabled>Select Status of Project</option>
            <option value="running">Running</option>
            <option value="completed">Completed</option>
            <option value="upcoming">Upcoming</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Project</button>
        <button @click="addProjectForm = false" class="btn btn-secondary">
          Cancel
        </button>
      </form>
    </div>

    <div class="row">
      <div
        class="col-lg-4 col-md-6"
        v-for="project in projects"
        :key="project.id"
      >
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">{{ project.name }}</h5>
            <p class="card-text">
              <strong>Details:</strong> {{ project.details }}
            </p>
            <p class="card-text">
              Status: <strong>{{ project.status }}</strong>
            </p>
            <p class="card-text" v-if="project.teams.length">
              <strong>Team Members: </strong>
              <span v-for="team in project.teams" :key="team.id">
                {{ team.employees.length }}
              </span>
            </p>
            <p class="card-text" v-if="project.teams.length">
              <strong>Team Name: </strong>
              <span v-for="team in project.teams" :key="team.id">
                {{ team.name }}
              </span>
            </p>
            <p class="card-text" v-if="project.teams.length">
              <strong>Team Leader Name: </strong>
              <span v-for="team in project.teams" :key="team.id">
                {{ team.team_leader.name }}
              </span>
            </p>
            <div class="d-flex justify-content-between" v-if="isHR === 'HR'">
              <button
                type="button"
                class="btn icon"
                @click="showEditModal(project)"
              >
                <i class="fas fa-edit"></i>
              </button>
              <button
                v-if="
                  project.status !== 'completed' && project.teams.length === 0
                "
                class="btn btn-primary"
                @click="showDropDown(project)"
              >
                Assign Team
              </button>
              <button
                type="button"
                class="btn icon"
                @click="showDeleteConfirmation(project.id)"
              >
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Custom Edit Modal Template -->
    <div class="custom-modal" v-if="isEditModalOpen">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Project</h5>
          <button type="button" class="close" @click="closeEditModal">
            &times;
          </button>
        </div>
        <div class="modal-body">
          <!-- Form to edit project -->
          <form @submit.prevent="updateProject">
            <!-- Inputs for editing project details -->
            <div class="form-group">
              <label for="editName">Name:</label>
              <input
                type="text"
                class="form-control"
                id="editName"
                v-model="editedProject.name"
              />
            </div>
            <div class="form-group">
              <label for="editDetails">Details:</label>
              <textarea
                class="form-control"
                id="editDetails"
                rows="5"
                v-model="editedProject.details"
              ></textarea>
            </div>
            <div class="form-group">
              <label for="editStatus">Status:</label>
              <select
                class="form-control"
                id="editStatus"
                v-model="editedProject.status"
              >
                <option value="" disabled>Select Status of Project</option>
                <option value="running">Running</option>
                <option value="completed">Completed</option>
                <option value="upcoming">Upcoming</option>
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
                @click="closeEditModal"
              >
                Close
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- assign team -->
    <div class="custom-modal" v-if="isModalOpen">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Assign Team to Project</h5>
          <button type="button" class="close" @click="closeModal">
            &times;
          </button>
        </div>
        <div class="modal-body">
          <!-- Form to edit project -->
          <form @submit.prevent="assignTeamToProject(selectedTeamId)">
            <!-- Inputs for editing project details -->
            <div class="form-group">
              <select
                class="form-control my-2"
                id="employeeSelect"
                v-model="selectedTeamId"
              >
                <option value="">Select Team</option>
                <option v-for="team in teams" :key="team.id" :value="team.id">
                  {{ team.name }}
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
          <p>Are you sure you want to delete this project?</p>
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
import { ref, onMounted } from "vue";
import axios from "axios";

const projects = ref([]);
const isHR = ref(localStorage.getItem("role"));
const token = localStorage.getItem("token");
const teams = ref([]);
const selectedTeamId = ref(""); // Reactive variable to store the selected employee ID
const isModalOpen = ref(false);
const selectedProject = ref(null); // Variable to store the selected project
const deleteConfirmation = ref(false);
const selectedProjectId = ref("");

const addProjectForm = ref(false);
const newProject = ref({
  name: "",
  details: "",
  status: "",
});

const editedProject = ref({
  id: null,
  name: "",
  details: "",
  status: "",
});
console.log(isHR.value);

const isEditModalOpen = ref(false);
const headers = {
  Authorization: `Bearer ${token}`,
};

onMounted(async () => {
  await fetchProjects();
  await fetchTeams();
});

const fetchProjects = async () => {
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/projects", {
      headers,
    });
    projects.value = response.data;
    console.log(projects.value);
  } catch (error) {
    console.error("Error fetching projects:", error);
  }
};

const fetchTeams = async () => {
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/teams", {
      headers,
    });
    teams.value = response.data.teams;
    console.log(teams.value);
  } catch (error) {
    console.error("Error fetching teams:", error);
  }
};

const showAddForm = () => {
  addProjectForm.value = true;
};

const showDropDown = (project) => {
  isModalOpen.value = true;
  selectedProject.value = project; // Set the selected project
};

const closeModal = () => {
  isModalOpen.value = false;
};

// Method to assign selected team to project
const assignTeamToProject = async (TeamId) => {
  const project = selectedProject.value; // Get the selected project
  console.log(TeamId, project);

  try {
    const response = await axios.post(
      "http://127.0.0.1:8000/api/teams/assign-team-to-project",
      { team_id: TeamId, project_id: project.id },
      { headers }
    );
    console.log(response.data);
    selectedTeamId.value = "";
    selectedProject.value = null;
    isModalOpen.value = false;
    fetchProjects(); // Refresh project list after assigning team
  } catch (error) {
    console.error("Error assigning team to project:", error);
  }
};

const addProject = async () => {
  try {
    const response = await axios.post(
      "http://127.0.0.1:8000/api/projects",
      newProject.value,
      { headers }
    );
    projects.value.push(response.data);
    console.log(response.data);
    addProjectForm.value = false;
    newProject.value = { name: "", details: "", status: "" }; // Clear form fields after adding project
  } catch (error) {
    console.error("Error adding project:", error);
  }
};

const showEditModal = (project) => {
  editedProject.value = { ...project };
  isEditModalOpen.value = true;
  console.log(isEditModalOpen.value);
};

const closeEditModal = (project) => {
  isEditModalOpen.value = false;
  console.log(isEditModalOpen.value);
};

const updateProject = async () => {
  try {
    const response = await axios.put(
      `http://127.0.0.1:8000/api/projects/${editedProject.value.id}`,
      editedProject.value,
      { headers }
    );
    console.log(response.data);
    const index = projects.value.findIndex(
      (p) => p.id === editedProject.value.id
    );
    if (index !== -1) {
      projects.value[index] = { ...editedProject.value };
    }
    isEditModalOpen.value = false; // Hide the modal
  } catch (error) {
    console.error("Error updating project:", error);
  }
};

// Add this method to show the delete confirmation modal
const showDeleteConfirmation = (projectId) => {
  selectedProjectId.value = projectId;
  deleteConfirmation.value = true;
};
const deleteProject = async (projectId) => {
  try {
    await axios.delete(`http://127.0.0.1:8000/api/projects/${projectId}`, {
      headers,
    });
    projects.value = projects.value.filter((p) => p.id !== projectId);
  } catch (error) {
    console.error("Error deleting project:", error);
  }
};
// Add these methods for confirming or canceling project deletion
const confirmDelete = async () => {
  try {
    await deleteProject(selectedProjectId.value);
    deleteConfirmation.value = false;
  } catch (error) {
    console.error("Error deleting project:", error);
  }
};

const cancelDelete = () => {
  deleteConfirmation.value = false;
};
</script>

<style scoped>
.card {
  background-color: #babdf1;
  padding: 5px 15px;
  border-radius: 10px;
  margin-bottom: 10px;
  /* align-items: center; */
  /* text-align: center; */
}

.icon {
  font-size: 18px;
}

.add {
  font-size: 40px;
  margin-right: 25px;
  padding-top: 0;
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
