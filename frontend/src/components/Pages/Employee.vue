<template>
  <div class="container mt-4">
    <h2>Employees Page</h2>
    <div class="row mt-3">
      <div
        class="col-lg-4 col-md-6"
        v-for="employee in employees"
        :key="employee.id"
      >
        <div class="emp-card mb-3">
          <img
            :src="
              'https://xsgames.co/randomusers/avatar.php?g=male&id=' +
              employee.id
            "
            class=""
            alt="Employee Image"
          />
          <div class="mt-2">
            <h5>{{ employee.name }}</h5>
            <p>{{ employee.role }}</p>
            <div v-if="employee.expanded">
              <p>{{ employee.email }}</p>
              <p><strong>Username:</strong> {{ employee.username }}</p>
              <p><strong>Skills:</strong> {{ employee.skills }}</p>
              <div>
                <strong>Team: </strong>
                <span v-if="employee.team.length === 0"
                  >Currently not in a team</span
                >
                <span v-else>
                  <span v-for="(team, index) in employee.team" :key="index">
                    {{ team.name
                    }}{{ index < employee.team.length - 1 ? ", " : "" }}
                  </span>
                </span>
              </div>
            </div>
            <button
              type="button"
              class="btn show"
              @click="toggleDetails(employee)"
            >
              {{ employee.expanded ? "Show Less" : "Show More" }}
              <i
                :class="
                  employee.expanded
                    ? 'fa-solid fa-circle-chevron-up'
                    : 'fa-solid fa-circle-chevron-down'
                "
              ></i>
            </button>
            <div class="d-flex justify-content-between">
              <button
                type="button"
                class="btn icon"
                @click="editEmployee(employee)"
              >
                <i class="fas fa-edit"></i>
              </button>
              <button
                type="button"
                class="btn icon"
                @click="deleteEmployee(employee.id)"
              >
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Custom Edit Modal Template -->
    <div class="custom-modal" v-if="editEmployeeModal">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Employee</h5>
          <button type="button" class="close" @click="cancelEdit">
            &times;
          </button>
        </div>
        <div class="modal-body">
          <!-- Form to edit employee details -->
          <form @submit.prevent="updateEmployee">
            <!-- Inputs for editing employee details -->
            <div class="form-group">
              <label for="name">Name:</label>
              <input
                type="text"
                class="form-control"
                id="name"
                v-model="selectedEmployee.name"
              />
            </div>
            <div class="form-group">
              <label for="username">Username:</label>
              <input
                type="text"
                class="form-control"
                id="username"
                v-model="selectedEmployee.username"
              />
            </div>
            <div class="form-group">
              <label for="email">Email:</label>
              <input
                type="email"
                class="form-control"
                id="email"
                v-model="selectedEmployee.email"
              />
            </div>
            <div class="form-group">
              <label for="skills">Skills:</label>
              <input
                type="text"
                class="form-control"
                id="skills"
                v-model="selectedEmployee.skills"
              />
            </div>
            <div class="form-group">
              <label for="role">Role:</label>
              <input
                type="text"
                class="form-control"
                id="role"
                v-model="selectedEmployee.role"
                disabled
              />
            </div>
            <!-- Other input fields for email, skills, etc. -->
            <!-- Add input fields as needed -->

            <!-- Buttons to save changes or close modal -->
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">
                Save Changes
              </button>
              <button
                type="button"
                class="btn btn-secondary"
                @click="cancelEdit"
              >
                Close
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
     <!-- Delete confirmation modal -->
    <div class="custom-modal" v-if="deleteConfirmation">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete Employee</h5>
          <button type="button" class="close" @click="cancelDelete">
            &times;
          </button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this employee?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" @click="confirmDelete">Delete</button>
          <button type="button" class="btn btn-secondary" @click="cancelDelete">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const employees = ref([]);
const selectedEmployee = ref(null);
const editEmployeeModal = ref(false);
const deleteConfirmation = ref(false);
const token = localStorage.getItem("token");
const headers = {
  Authorization: `Bearer ${token}`,
};

onMounted(async () => {
  await fetchEmployees();
});

const fetchEmployees = async () => {
  try {
    const response = await axios.get("http://127.0.0.1:8000/api/employees", {
      headers,
    });
    employees.value = response.data.users.map((employee) => ({
      ...employee,
      expanded: false,
    }));
    console.log(employees.value);
  } catch (error) {
    console.error("Error fetching employees:", error);
  }
};

const updateEmployee = async () => {
  try {
    const response = await axios.put(
      `http://127.0.0.1:8000/api/employees/${selectedEmployee.value.id}`,
      selectedEmployee.value,
      { headers }
    );
    console.log(response.data);
    selectedEmployee.value = null;
    fetchEmployees();
    // Optionally, display a success message or update the local data
  } catch (error) {
    console.error("Error updating employee:", error);
  }
};

const editEmployee = (employee) => {
  selectedEmployee.value = { ...employee }; // Clone the object to avoid mutating the original
  editEmployeeModal.value=true
};

const deleteEmployee = async (id) => {
  selectedEmployee.value = employees.value.find((employee) => employee.id === id);
  deleteConfirmation.value = true;
};

const confirmDelete = async () => {
  try {
    const response = await axios.delete(
      `http://127.0.0.1:8000/api/employees/${selectedEmployee.value.id}`
    );
    console.log(response.data);
    deleteConfirmation.value = false;
    // Fetch employees again after successful deletion
     fetchEmployees();
  } catch (error) {
    console.error("Error deleting employee:", error);
  }
};

const cancelDelete = () => {
  deleteConfirmation.value = false;
};

const toggleDetails = (employee) => {
  employee.expanded = !employee.expanded;
};

const cancelEdit = () => {
  selectedEmployee.value = null;
  editEmployeeModal.value=false
};
</script>

<style scoped>
.emp-card {
  /* width:300px; */
  background-color: #babdf1;
  padding: 10px 20px;
  border-radius: 10px;
  margin-bottom: 10px;
  /* align-items: center; */
  text-align: center;
}

img {
  border-radius: 50%;
  width: 100px;
}
.icon {
  font-size: 18px;
}

.show:hover {
  color: rgb(27, 27, 121);
}

.employee-details {
  margin-top: 20px;
}

.form-group {
  margin-bottom: 15px;
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
  max-width: 700px; /* Adjust as needed */
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
