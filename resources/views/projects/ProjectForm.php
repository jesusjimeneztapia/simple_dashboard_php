<?php
$projectId = isset($project) ? $project->id : "";
$name = isset($project) ? $project->name : "";
$description = isset($project) ? $project->description : "";
$status = isset($project) ? $project->status : "";
$client_company = isset($project) ? $project->client_company : "";
$project_leader = isset($project) ? $project->project_leader : "";
$estimated_budget = isset($project) ? $project->estimated_budget : "";
$total_amount_spent = isset($project) ? $project->total_amount_spent : "";
$estimated_project_duration = isset($project) ? $project->estimated_project_duration : "";

?>

<form id="project-form">
  <div class="row">
    <div class="col-md-6">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">General</h3>

          <div class="card-tools">
            <button
              type="button"
              class="btn btn-tool"
              data-card-widget="collapse"
              title="Collapse"
            >
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="inputName">Project Name</label>
            <input type="text" id="inputName" name="name" class="form-control" value="<?= $name ?>" />
          </div>
          <div class="form-group">
            <label for="inputDescription">Project Description</label>
            <textarea
              id="inputDescription"
              name="description"
              class="form-control"
              rows="4"
            ><?= $description ?></textarea>
          </div>
          <div class="form-group">
            <label for="inputStatus">Status</label>
            <select id="inputStatus" name="status" class="form-control custom-select">
              <option selected="" disabled="">Select one</option>
              <option value="On Hold" <?= $status == "On Hold" ? "selected" : "" ?>>On Hold</option>
              <option value="Canceled" <?= $status == "Canceled" ? "selected" : "" ?>>Canceled</option>
              <option value="Success" <?= $status == "Success" ? "selected" : "" ?>>Success</option>
            </select>
          </div>
          <div class="form-group">
            <label for="inputClientCompany">Client Company</label>
            <input type="text" id="inputClientCompany" name="client_company" class="form-control" value="<?= $client_company ?>" />
          </div>
          <div class="form-group">
            <label for="inputProjectLeader">Project Leader</label>
            <input type="text" id="inputProjectLeader" name="project_leader" class="form-control" value="<?= $project_leader ?>" />
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <div class="col-md-6">
      <div class="card card-secondary">
        <div class="card-header">
          <h3 class="card-title">Budget</h3>

          <div class="card-tools">
            <button
              type="button"
              class="btn btn-tool"
              data-card-widget="collapse"
              title="Collapse"
            >
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="inputEstimatedBudget">Estimated budget</label>
            <input type="number" id="inputEstimatedBudget" name="estimated_budget" class="form-control" value="<?= $estimated_budget ?>" />
          </div>
          <div class="form-group">
            <label for="inputSpentBudget">Total amount spent</label>
            <input type="number" id="inputSpentBudget" name="total_amount_spent" class="form-control" value="<?= $total_amount_spent ?>" />
          </div>
          <div class="form-group">
            <label for="inputEstimatedDuration">Estimated project duration</label>
            <input
              type="number"
              id="inputEstimatedDuration"
              name="estimated_project_duration"
              class="form-control"
              value="<?= $estimated_project_duration ?>"
            />
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <a href="<?= base_url() ?>" class="btn btn-secondary">Cancel</a>
      <input
        type="submit"
        value="<?= empty($projectId) ? "Create new Project" : "Save Changes" ?>"
        class="btn btn-success float-right"
      />
    </div>
  </div>
</form>

<script>
  const API_BASE = "<?= base_url(true) . "/api/projects" ?>"
  const projectId = "<?= $projectId ?>"

  const $createProjectForm = document.querySelector("#project-form")
  $createProjectForm.addEventListener("submit", async function (e) {
    e.preventDefault()
    const formData = new FormData(this)
    const data = Object.fromEntries(formData.entries())

    const response = await fetch(`${API_BASE}${!projectId ? "" : `/${projectId}`}`, {
      method: !projectId ? "POST" : "PUT",
      body: JSON.stringify(data)
    })
    if (response.ok) {
      window.location.replace("<?= base_url() ?>")
    }
  })
</script>