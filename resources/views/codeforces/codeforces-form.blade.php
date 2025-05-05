<x-app-layout>
  @section('content')
 <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
   <form id="problemForm" action="{{route('save.C.test_cases')}}" method="post" class="bg-white shadow-md rounded-lg p-6">
     @csrf
     <div id="loadingOverlay" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
       <div class="bg-white p-6 rounded-lg shadow-xl flex flex-col items-center">
         <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500 mb-4"></div>
         <p class="text-gray-700 font-medium" id="loadingMessage">Processing...</p>
       </div>
     </div>

     <fieldset class="mb-6 border border-gray-300 rounded-md p-4">
       <legend class="text-lg font-medium text-gray-900 px-2">Problem Details</legend>

       <div class="mb-4">
         <h4 class="block text-sm font-medium text-gray-700 mb-1">Question Id</h4>
         <div class="relative">
           <input type="text" name="id" placeholder="Question id. Example: 1 for Two Sum...." id="questionId"
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
           <div id="idLoadingSpinner" class="hidden absolute right-3 top-3">
             <div class="animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-indigo-500"></div>
           </div>
         </div>
       </div>

       <div class="mb-4">
         <h4 class="block text-sm font-medium text-gray-700 mb-1">Question Title</h4>
         <div class="relative">
           <textarea name="title" id="titleArea" rows="2"
                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50"
                     readonly></textarea>
           <div id="titleLoadingIndicator" class="hidden absolute right-3 top-3">
             <div class="h-1 w-20 bg-gray-200 rounded overflow-hidden">
               <div class="animate-pulse bg-indigo-500 h-full"></div>
             </div>
           </div>
         </div>
       </div>

       <div class="mb-2">
         <h4 class="block text-sm font-medium text-gray-700 mb-1">Description</h4>
         <div class="relative">
           <textarea name="description" id="descriptionArea" rows="8"
                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50"
                     readonly></textarea>
           <div id="descLoadingIndicator" class="hidden absolute right-3 top-3">
             <div class="h-1 w-20 bg-gray-200 rounded overflow-hidden">
               <div class="animate-pulse bg-indigo-500 h-full"></div>
             </div>
           </div>
         </div>
       </div>
     </fieldset>

     <fieldset class="mb-6 border border-gray-300 rounded-md p-4">
       <legend class="text-lg font-medium text-gray-900 px-2">Test Case Inputs</legend>

       <div class="mb-4">
         <h4 class="block text-sm font-medium text-gray-700 mb-1">Constraints</h4>
         <textarea name="constraints" id="constraintsArea" rows="4"
                   placeholder="List constraints, separated by a '.'"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
       </div>

       <div class="mb-2">
         <h4 class="block text-sm font-medium text-gray-700 mb-1">Follow ups (If not, leave blank)</h4>
         <textarea name="followUps" id="followUpsArea" rows="4"
                   placeholder="List follow-ups, separated by a '.'"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
       </div>
     </fieldset>

     <div class="flex justify-center mb-6">
       <button type="button" id="generateTestCasesBtn"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-gray-100 hover:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
         <span id="generateBtnText">Generate Test Cases</span>
         <svg id="generateSpinner" class="hidden animate-spin ml-2 mr-1 h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
           <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
           <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
         </svg>
       </button>
     </div>

     <fieldset class="mb-6 border border-gray-300 rounded-md p-4">
       <legend class="text-lg font-medium text-gray-900 px-2">Generated Test Cases</legend>

       <div class="mb-4">
         <h4 class="block text-sm font-medium text-gray-700 mb-1">Edge Cases</h4>
         <div class="relative">
           <textarea name="EdgeCases" id="EdgecasesArea" rows="8"
                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50"
                     readonly></textarea>
           <div id="edgeCasesLoadingIndicator" class="hidden absolute right-3 top-3">
             <div class="animate-pulse bg-indigo-500 h-4 w-4 rounded-full"></div>
           </div>
         </div>
       </div>

       <div class="mb-2">
         <h4 class="block text-sm font-medium text-gray-700 mb-1">Normal cases</h4>
         <div class="relative">
           <textarea name="NormalCases" id="NormalCasesArea" rows="8"
                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50"
                     readonly></textarea>
           <div id="normalCasesLoadingIndicator" class="hidden absolute right-3 top-3">
             <div class="animate-pulse bg-indigo-500 h-4 w-4 rounded-full"></div>
           </div>
         </div>
       </div>
     </fieldset>

     <div class="flex justify-end">
       <button type="submit" id="submitBtnArea" name="SubmitButton"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-green-600 bg-white hover:bg-gray-100 hover:border-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
         <span>Submit</span>
         <svg id="submitSpinner" class="hidden animate-spin ml-2 mr-1 h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
           <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
           <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
         </svg>
       </button>
     </div>
   </form>
 </div>

 <script src="{{ asset('js/gen_description.js') }}"></script>
 @endsection
</x-app-layout>