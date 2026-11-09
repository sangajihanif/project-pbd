  <div class="modal fade modal-under-nav modal-search modal-close-out" id="searchPagesModal" tabindex="-1" role="dialog"
      aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <form action="{{ route('barang.index') }}" method="GET">
                  <div class="modal-header border-0 p-0">
                      <button type="button" class="btn-close btn btn-icon btn-icon-only btn-foreground"
                          data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body ps-5 pe-5 pb-0 border-0">
                      <input id="searchPagesInput" name="q" values="{{ request('q') }}"
                          class="form-control form-control-xl borderless ps-0 pe-0 mb-1 auto-complete" type="text"
                          autocomplete="off" />
                  </div>
                  <div class="modal-footer border-top justify-content-between ps-5 pe-5 pb-3 pt-3 border-0">
                      <span class="text-alternate d-inline-block m-0 me-3">
                          <i data-acorn-icon="arrow-bottom" data-acorn-size="15"
                              class="text-alternate align-middle me-1"></i>
                          <span class="align-middle text-medium">Navigate</span>
                      </span>
                      <span class="text-alternate d-inline-block m-0 me-3">
                          <i data-acorn-icon="arrow-bottom-left" data-acorn-size="15"
                              class="text-alternate align-middle me-1"></i>
                          <span class="align-middle text-medium">Select</span>
                      </span>
                      <button type="sumbit" class="btn btn-primary btn-sm ms-auto">Cari</button>
                  </div>

              </form>
          </div>
      </div>
  </div>
