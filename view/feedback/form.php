<?php
$deactivate_reasons = [
   'no_longer_needed' => [
      'title' => esc_html__('I no longer need the plugin', 'easyform'),
      'input' => '',
   ],
   'found_a_better' => [
      'title' => esc_html__('I found a better plugin', 'easyform'),
      'input' => esc_html__('Please share which plugin', 'easyform'),
   ],
   'couldnt_get_to_work' => [
      'title' => esc_html__('I couldn\'t get the plugin to work', 'easyform'),
      'input' => '',
   ],
   'temporary_deactivation' => [
      'title' => esc_html__('It\'s a temporary deactivation', 'easyform'),
      'input' => '',
   ],
   'has_pro' => [
      'title' => esc_html__('I have Easyform Pro', 'easyform'),
      'input' => '',
      'alert' => esc_html__('Wait! Don\'t deactivate Easyform. You have to activate both Easyform and Easyform Pro in order for the plugin to work.', 'easyform'),
   ],
   'other' => [
      'title' => esc_html__('Other', 'easyform'),
      'input' => esc_html__('Please share the reason', 'easyform'),
   ],
];

?> 
<div class="rhef"> 
   <div class="pv-overlay pv-feedback-modal" style="display: none">
      <div class="pv-modal-content"> 
         <div class="pv-modal-header pv-gradient">
               <span class="pv-close">
                  <svg
                     width="25"
                     height="25"
                     viewBox="0 0 16 16"
                     fill="none" 
                  >
                     <path
                        d="M12.5 3.5L3.5 12.5"
                        stroke="#718096"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                     />
                     <path
                        d="M12.5 12.5L3.5 3.5"
                        stroke="#718096"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                     />
                  </svg>
               </span>
               <h2 class="pv-modal-title"><?php echo esc_html__('Quick Feedback', 'elementor'); ?></h2>
               <p><?php echo esc_html__('If you have a moment, please share why you are deactivating Easyform', 'easyform'); ?></p>
         </div>

         <form>
            <div class="pv-content">
               <div class="pv-form-style-one"> 
                  <?php wp_nonce_field('_rhef_deactivate_nonce'); ?>
                  <div class="row"> 
                     <?php foreach ($deactivate_reasons as $reason_key => $reason) : ?>
                        <div class="col-12">
                           <div class="pv-field-radio">
                              <input 
                                 type="radio" 
                                 id="rhef-deactivate-<?php echo esc_attr($reason_key); ?>" 
                                 class="rhef-deactivate-reason" 
                                 name="reason_key" 
                                 value="<?php echo esc_attr($reason_key); ?>" 
                              />
                              <label for="rhef-deactivate-<?php echo esc_attr($reason_key); ?>"><?php echo esc_html($reason['title']); ?></label> 
                           </div>

                           <?php if (! empty($reason['input'])) : ?>
                              <div class="rhef-feedback-text" style="display: none" >
                                 <input 
                                    type="text"  
                                    name="reason_<?php echo esc_attr($reason_key); ?>" 
                                    placeholder="<?php echo esc_attr($reason['input']); ?>" 
                                 />
                              </div>
                           <?php endif; ?>

                           <?php if (! empty($reason['alert'])) : ?>
                              <div class="rhef-feedback-alert" style="display: none; color: #ff0000"><?php echo esc_html($reason['alert']); ?></div>
                           <?php endif; ?>
                        </div> 
                     <?php endforeach; ?> 

                     <div class="rhef-data-alert" style="display: none; margin-top: 10px">
                        <div class="col-12">
                           <div class="pv-field-radio">
                              <input 
                                 type="checkbox" 
                                 id="rhef-data-collect"
                                 name="data_collect"
                                 value='1'
                                 style="zoom: 1"
                                 checked
                              />
                              <label for="rhef-data-collect" style="font-size: 11px">Share your Name and Email for communication purposes</label> 
                           </div>
                        </div>
                     </div>
                  </div> 
               </div>
            </div>

            <div class="pv-modal-footer pv-mt-25">
               <div class="row">
                  <div class="col">
                     <button class="pv-feedback-skip pv-btn pv-text-hover-blue"><?php _e('Skip & Deactivate', 'easyform'); ?></button>
                  </div>
                  <div class="col">
                     <button class="pv-feedback-submit pv-btn pv-bg-blue pv-bg-hover-blue pv-btn-big pv-float-right pv-color-white">
                        <?php _e('Submit & Deactivate', 'easyform'); ?>
                     </button>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div> 
<?php 