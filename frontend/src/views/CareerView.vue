<template>
  <div class="container mt-4">
    <h2 class="text-center mb-4">Career Opportunities</h2>
    <div class="row">
      <div class="col-md-6 mb-4" v-for="(job, index) in jobs" :key="index">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{ job.title }}</h5>
            <p class="card-text">{{ job.description }}</p>
            <p class="card-text">Location: {{ job.location }}</p>
            <p class="card-text">Type: {{ job.type }}</p>
            <RouterLink to="signup" class="btn btn-primary">Apply Now</RouterLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const jobs = [
  {
    title: "Software Developer",
    description:
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi.",
    location: "New York",
    type: "Full-time",
  },
  {
    title: "UX Designer",
    description:
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi.",
    location: "San Francisco",
    type: "Part-time",
  },
  {
    title: "Data Scientist",
    description:
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi.",
    location: "Remote",
    type: "Contract",
  },
  // Add more job postings as needed
];
</script>

<style scoped>
/* Add custom styles here if needed */
.card {
  background-color: #babdf1; /* Light gray background */
  padding: 5px 15px;
  border-radius: 10px;
  margin-bottom: 10px;
  /* align-items: center; */
  /* text-align: center */
}
</style>
