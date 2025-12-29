<template>
  <div class="mt-2 space-y-3">
    <!-- Hidden File Input -->
    <input
      ref="fileInput"
      type="file"
      class="hidden"
      :multiple="isMultiUpload"
      :accept="accept"
      @change="handleFileChange"
    />

    <!-- Choose file button -->
    <Button
      btn-type="isBrand"
      :disabled="!isMultiUpload && files.length >= 1"
      :text="isMultiUpload 
        ? translations?.general?.chooseFiles 
        : translations?.general?.chooseFile"
      class="px-4 py-2"
      @click="chooseFile"
    />

    <!-- Selected files list -->
    <ul v-if="files.length" class="space-y-2 text-sm">
      <li
        v-for="(file, index) in files"
        :key="index"
        class="flex items-center justify-between border rounded px-3 py-2"
      >
        <div class="flex flex-col">
          <span class="font-medium truncate max-w-xs">
            {{ file.name ?? file }}
          </span>
          <span v-if="file.size" class="text-xs text-baseColor-500">
            {{ formatSize(file.size) }}
          </span>
        </div>

        <Button
          btn-type="isSecBrandTextOnly"
          btn-icon="XMarkIcon"
          btn-rounded="full"
          size="xs"
          @click="removeFile(index)"
        />
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Button from '@/Components/Forms/Button.vue'

const props = defineProps({
  modelValue: { type: [Array, File, String, null], default: null },
  isMultiUpload: { type: Boolean, default: false },
  accept: { type: String, default: '.pdf,.doc,.docx,.xls,.xlsx,.csv,image/*' },
})

const emit = defineEmits(['update:modelValue'])

const page = usePage()
const translations = computed(() => page.props.translations || {})

const fileInput = ref(null)

const normalizeToArray = (val) => {
  if (Array.isArray(val)) return val
  if (!val) return []
  return [val]
}

const files = ref(normalizeToArray(props.modelValue))

const chooseFile = () => {
  fileInput.value?.click()
}

const handleFileChange = (e) => {
  const selected = Array.from(e.target.files)

  files.value = props.isMultiUpload
    ? [...files.value, ...selected]
    : selected.slice(0, 1)

  emitFiles()
}

const removeFile = (index) => {
  files.value.splice(index, 1)
  emitFiles()
}

const emitFiles = () => {
  emit(
    'update:modelValue',
    props.isMultiUpload ? files.value : files.value[0] ?? null
  )
}

const formatSize = (bytes) => {
  if (!bytes) return ''
  const kb = bytes / 1024
  if (kb < 1024) return `${kb.toFixed(1)} KB`
  return `${(kb / 1024).toFixed(1)} MB`
}

watch(
  () => props.modelValue,
  (val) => {
    files.value = normalizeToArray(val)
  }
)
</script>
