   <div id="linkChildModal" class="modal" style="display:none;">
       <div class="modal-content"
           style="background:#fff; padding:2rem; border-radius:10px; max-width:500px; margin:auto;">
           <h2>Link Children</h2>
           <form id="linkChildForm" method="POST" action="{{ route('Parent.link.request') }}">
               @csrf
               <div class="form-group">
                   <label for="student_ids">Select Students</label>
                   <select name="student_ids[]" id="student_ids" class="form-control" multiple required></select>
               </div>


               <div class="form-group">
                   <label for="parent_email">Your Email</label>
                   <input type="email" name="parent_email" id="parent_email" value="{{ Auth::user()->email }}"
                       readonly>
               </div>
               <div class="form-group">
                   <label for="parent_contact">Your Contact Number</label>
                   <input type="text" name="parent_contact" id="parent_contact"
                       value="{{ Auth::user()->contact_num }}" readonly>
               </div>
               <button type="submit" class="btn-primary">Send Request</button>
               <button type="button" onclick="closeLinkChildModal()" class="btn-secondary"
                   style="margin-left:10px;">Cancel</button>
           </form>
       </div>
   </div>
